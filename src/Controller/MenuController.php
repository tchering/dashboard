<?php

namespace App\Controller;

use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(EntityManagerInterface $em): Response
    {
        $menus = $em->getRepository(Menu::class)->findBy([], ['rang' => 'asc']);
        $menu = $this->list_menu(null, 0, $menus);
        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
            'title' => 'Menus',
            'menu' => $menu
        ]);
    }


    public function list_menu($parentId, $level, $menus)
    {
        $html = "";
        foreach ($menus as $menu) {
            $id = $menu->getId();
            $rang = $menu->getRang();
            $libelle = $menu->getLibelle();
            $url = $menu->getUrl();
            $icon = $menu->getIcon();
            $icon = "<i class='$icon fa-2x'></i>";
            $role = $menu->getRole();
            $parentMenuId = $menu->getParent();
            if ($parentId == $parentMenuId) {
                $point = "";
                for ($i = 1; $i <= $level; $i++) {
                    $point .= "..........";
                }
                $class = ($level == 0) ? "fw-bold" : "";
                $html .= "<tr class='$class'>";
                $html .= "<td><input type='checkbox' name='check' value='$id' id='$id' onclick='onlyOne(this)'></td>";
                $html .= "<td>$point $libelle</td>";
                $html .= "<td>$url</td>";
                $html .= "<td>$icon</td>";
                $html .= "<td>$role</td>";
                $html .= "</tr>";
                $html .= $this->list_menu($menu, $level + 1, $menus);
            }
        }
        return $html;
    }
}
