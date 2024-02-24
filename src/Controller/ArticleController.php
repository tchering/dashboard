<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $ar): Response
    {
        $articles = $ar->findAll();
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
            'total' => count($articles),
            'title' => 'Articles'
        ]);
    }

    #[Route('/article/show/{id}', name: 'app_article_show')]
    public function show(ArticleRepository $ar, $id): Response
    {
        $article = $ar->find($id);
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'disabled' => "disabled",
            'title' => "Detail",
        ]);
    }

    #[Route('/article/delete/{id}', name: 'app_article_delete')]
    public function delete(EntityManagerInterface $em, $id): Response
    {
        //! here we call getRepository method with param Article class. Now this will search for corresponding repository . Now once the repository has been found then find method is called on that repository  .
        $article = $em->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute("app_article");
    }

    #[Route('/article/add', name: 'app_article_add')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        //! The reason for instantiating the Article class in the function is because this function is likely used for creating a new Article, not editing an existing one.
        $article = new Article;
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute("app_article");
        }

        return $this->render('article/form.html.twig', [
            'title' => 'Add New Article',
            'article' => $article,
            'form' => $form->createView()
        ]);
    }
    #[Route('/article/modify/{id}', name: 'app_article_modify')]

    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('article/form.html.twig', [
            'article' => $article,
            'title' => 'Modify Article',
            'disabled' => '',
            'form' => $form->createView()
        ]);
    }
    //! the function search is this for article 
    #[Route('/article/search', name: 'app_article_search')]
    public function search(Request $request, EntityManagerInterface $em)
    {
        $mot = $request->get('mot');
        $articles = $em->getRepository(Article::class)->searchMot($mot);
        $rows = [];
        foreach ($articles as $article) {
            $rows[] = [
                'id' => $article->getId(),
                'numArticle' => $article->getNumArticle(),
                'designation' => $article->getDesignation(),
                'price' => $article->getPrice(),
            ];
        }
        $response = [
            'rows' => $rows,
            'count' => count($articles),
        ];
        echo json_encode($response);
        exit;
    }
}
