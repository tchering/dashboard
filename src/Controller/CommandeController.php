<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\TextUI\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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


    #[Route('/ligne_commande/content/{id}', name: 'app_commande_content')]
    public function ligneCommande(): Response
    {
        return $this->render('commande/ligne_commande.html.twig', [
            'title' => 'Detail Invoice',
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
