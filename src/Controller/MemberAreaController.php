<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MemberAreaController extends AbstractController
{
    #[Route('/member/area', name: 'app_member_area')]
    public function index(UserInterface $user, UserRepository $userRepository): Response
    {
        if ($user){

            $currentUser = $userRepository->findBy(['username'=>$user->getUserIdentifier()])[0];


            return $this->render('member_area/index.html.twig', [
                'user' => $currentUser,
            ]);
        } else {
            return $this->render('security/index.html.twig');
        }
    }
}
