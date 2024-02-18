<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(CommandeRepository $cr): Response
    {
        $commandes = $cr->findAll();
        return $this->render('commande/index.html.twig', [
            'commandes'=>$commandes,
            'controller_name' => 'CommandeController',
            'title'=>'Commande',
        ]);
    }
}
