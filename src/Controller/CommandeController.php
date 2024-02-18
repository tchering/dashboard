<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\ArticleRepository;
use App\Repository\CommandeRepository;
use App\Repository\LigneCommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\TextUI\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(CommandeRepository $cr): Response
    {
        $commandes = $cr->findAll();
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
            'controller_name' => 'CommandeController',
            'title' => 'Commande',
        ]);
    }
    #[Route('/ligne_commande/search', name: 'app_commande_search_code')]
    public function searchCode(Request $request, ArticleRepository $ar): Response
    {
        //! In Symfony, $request->get('numArticle') is used to retrieve data from the request object. In this case we are sending value of numArticle by appending its value in data in ligne_comande.html.twig.And now Request will extract the variable sent in url .
        $numArticle = $request->get('numArticle');
        //! here findOneBy will take 2 parameter with key value.
        //!In Above code $request has extracted array 
        //! findOneBy will takes an associative array in its parameter
        $article = $ar->findOneBy(['numArticle' => $numArticle]);
        //todo either we can call findOneBy using this method below.
        // $article = $em->getRepository(Article::class)->findOneBy(['numArticle' => $numArticle]);
        if ($article) {
            $response =
                [
                    'id' => $article->getId(),
                    'designation' => $article->getDesignation(),
                    'price' => $article->getPrice(),
                ];
        } else {
            $response = [];
        }
        echo json_encode($response);
        exit;
    }

    #[Route('/ligne_commande/content/{id}', name: 'app_commande_content')]
    public function ligneCommande(EntityManagerInterface $em, $id): Response
    {
        $commande = $em->getRepository(Commande::class)->find($id);
        $ligneCommande = $commande->getLigneCommandes();
        $rows = [];
        $total = 0;
        foreach ($ligneCommande as $ligne) {
            $article = $ligne->getArticle();
            $quantity =  $ligne->getQuantity();
            $price = $ligne->getPrice();
            $totalPrice  = $price * $quantity;
            $total += $totalPrice;
            $rows[] = [
                'numArticle' => $article->getNumArticle(),
                'designation' => $article->getDesignation(),
                'price' => $article->getPrice(),
                'quantity' => number_format($quantity, 2, '.', ''),
                'total' => number_format($total, 2, '.', ''),
            ];
        }
        return $this->render('commande/ligne_commande.html.twig', [
            'title' => 'Detail Invoice',
            'commande' => $commande,
            'rows' => $rows,
            'total' => $total,
        ]);
    }

    #[Route('/commande/show/{id}', name: 'app_commande_show')]
    public function show(CommandeRepository $cr, $id): Response
    {
        $commande = $cr->find($id);
        return $this->render('commande/show.html.twig', [
            'title' => "Detail Commande",
            'commande' => $commande,
            'disabled' => "disabled",
        ]);
    }
    #[Route('/commande/delete/{id}', name: 'app_commande_delete')]
    public function delete(EntityManagerInterface $em, $id): Response
    {
        $commande = $em->getRepository(Commande::class)->find($id);
        $em->remove($commande);
        $em->flush();

        return $this->redirectToRoute('app_commande');
    }

    #[Route('/commande/add', name: 'app_commande_add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($commande);
            $em->flush();
            return $this->redirectToRoute("app_commande");
        }

        return $this->render("commande/form.html.twig", [
            'title' => "Add New Commande",
            'form' => $form->createView(),
        ]);
    }
    #[Route('/commande/modify/{id}', name: 'app_commande_modify')]
    public function edit(EntityManagerInterface $em, Request $request, $id, Commande $commande): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_commande');
        }
        return $this->render("commande/form.html.twig", [
            'title' => 'Modify Commande',
            'form' => $form->createView(),
        ]);
    }
}
