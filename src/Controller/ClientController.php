<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    public function index(ClientRepository $cr): Response
    {
        $clients = $cr->findAll();
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'title' => 'CLIENTS LIST ',
            'clients' => $clients,
            'total' => count($clients)
        ]);
    }

    #[Route('/client/show/{id}', name: 'app_client_show')]
    public function show(ClientRepository $cr, $id): Response
    {
        $client = $cr->find($id);
        return $this->render('client/show.html.twig', [
            'client' => $client,
            'title' => "Description Client",
            'disabled' => "disabled",
        ]);
    }

    #[Route('/client/delete/{id}', name: "app_client_delete")]
    public function delete(EntityManagerInterface $em, Client $client, $id): Response
    {
        $client = $em->getRepository(Client::class)->find($id);
        $em->remove($client);
        $em->flush();
        return $this->redirectToRoute('app_client');
    }
    #[Route('/client/add', name: 'app_client_add')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($client);
            $em->flush();
            return $this->redirectToRoute("app_client");
        }
        return $this->render('client/form.html.twig', [
            'title' => 'Add New Client',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/client/modify/{id}', name: 'app_client_modify')]
    public function edit(EntityManagerInterface $em, Client $client, Request $request): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_client');
        }

        return $this->render('client/form.html.twig', [
            'client' => $client,
            'title' => "Modify Client",
            'form' => $form->createView(),
        ]);
    }
    #[Route('client/search', name: 'app_client_search')]
    public function search(Request $request, EntityManagerInterface $em)
    {
        $mot = $request->get('mot');
        $clients = $em->getRepository(Client::class)->searchMot($mot);
        $rows = [];
        foreach ($clients as $client) {
            $rows[] = [
                'id' => $client->getId(),
                'numClient' => $client->getNumClient(),
                'nameClient' => $client->getNameClient(),
                'adresseClient' => $client->getAdresseClient(),
            ];
        }
        $response = [
            'rows' => $rows,
            'total' => count($clients)
        ];
        echo json_encode($response);
        exit;
    }
}
