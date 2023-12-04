<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\services\uploadPicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles'=> $articles,
        ]);
    }

    #[Route('/add/article', name: 'app_add_article')]
    public function addArticle(uploadPicture $uploadPicture, EntityManagerInterface $entityManager, Request $request, UserInterface $user, UserRepository $userRepository)
    {

        $currentUser = $userRepository->findBy(['username'=>$user->getUserIdentifier()])[0];
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $file = $form->get('picture')->getData();

            // Enregistrez l'image
            $url = $uploadPicture->upload($file, 'Article');

            $date = new \DateTime();
            $article->setPicture($url);
            $article->setDate($date);
            $article->setUser($currentUser);
            $entityManager->persist($article);
            $entityManager->flush();

        }


        return $this->render('article/addArticle.html.twig', [
            'form'=>$form->createView(),
        ]);

    }

    #[Route('/private/article/', name: 'app_private_article')]
    public function privateArticle(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findBy(['public'=>false]);


        return $this->render('article/privateArticle.html.twig', [
            'articles'=>$articles
        ]);
    }

}
