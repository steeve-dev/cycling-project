<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/add/article', name: 'app_add_article')]
    public function addArticle(EntityManagerInterface $entityManager, Request $request, UserInterface $user, UserRepository $userRepository)
    {

        $currentUser = $userRepository->findBy(['username'=>$user->getUserIdentifier()])[0];
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $date = new \DateTime();
            $article->setDate($date);
            $article->setUser($currentUser);

            $entityManager->persist($article);
            $entityManager->flush();

        }


        return $this->render('article/addArticle.html.twig', [
            'form'=>$form->createView(),
        ]);

    }



}