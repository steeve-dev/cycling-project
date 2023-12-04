<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrganisationController extends AbstractController
{
    #[Route('/organisation', name: 'app_organisation')]
    public function index(): Response
    {
        return $this->render('organisation/index.html.twig', [
            'controller_name' => 'OrganisationController',
        ]);
    }

    #[Route('/admin/organisation', name: 'edit-organisation')]
    public function gallery()
    {
        return $this->render('member_area/gallery.html.twig');
    }

}
