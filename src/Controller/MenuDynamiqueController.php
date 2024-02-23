<?php

namespace App\Controller;

use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuDynamiqueController extends AbstractController
{
    #[Route('/menu/dynamique', name: 'app_menu_dynamique')]
    public function index(EntityManagerInterface $em, UrlGeneratorInterface  $ugi): Response
    {
        $menus = $em->getRepository(Menu::class)->findBy([], ['rang' => 'asc']);
        $html = $this->show_menu($ugi, null, 0, $menus);
        return $this->render('menu_dynamique/index.html.twig', [
            'menu' => $html,
            'controller_name' => 'MenuDynamiqueController',
            'title' => 'Menu'
        ]);
    }
    public function show_menu($ugi, $parentId, $niveau, $menus)
    {
        $html = "";
        $niveau_precedent = 0;

        if ($niveau_precedent == 0 && $niveau == 0) {
            $html .= "<ul class='navbar-nav me-auto mb-2 mb-lg-0 d-flex justify-content-center align-items-center'>";
        }
        foreach ($menus as $menu) {
            $id = $menu->getId();
            $rang = $menu->getRang();
            $libelle = $menu->getLibelle();
            $role = $menu->getRole();
            $url = $menu->getUrl();

            try {
                $href = $ugi->generate($url);
            } catch (\Throwable $th) {
                $href = $url;
            }
            $icon = $menu->getIcon();
            $icon = ($icon) ? "<i class='$icon'></i>" : "";
            $parentMenuId = $menu->getParent();
            $enfants = $menu->getMenus();
            $nbreEnfants = count($enfants);
            if ($parentMenuId == $parentId) {
                if ($niveau_precedent < $niveau) {
                    $html .= "<ul class='dropdown-menu'>";
                }
                if ($nbreEnfants != 0) {
                    $html .= "<li class='dropdown'><a class='dropdown-toggle text-dark' href='$href' role='button' id='dropdownMenuLink' data-mdb-dropdown-init data-mdb-ripple-init aria-expanded='false'>$libelle $icon</a>";
                } else {
                    $html .= "<li class='nav-item'><a class='nav-link text-dark' aria-current='page' href='$href'>$icon $libelle</a>";
                }
                $niveau_precedent = $niveau;
                $html .= $this->show_menu($ugi, $menu, $niveau + 1, $menus);
            }
        }
        if ($niveau_precedent == 0 && $niveau == 0) {
            $html .= "</ul>";
        } elseif ($niveau_precedent == $niveau) {
            $html .= "</ul></li>";
        } else {
            $html .= "</li>";
        }
        return $html;
    }
}
