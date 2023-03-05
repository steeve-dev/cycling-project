<?php

namespace App\Controller;

use App\Entity\Circuit;
use App\Form\CircuitType;
use App\Repository\CircuitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CircuitController extends AbstractController
{
    #[Route('/circuit', name: 'app_circuit')]
    public function index(CircuitRepository $circuitRepository): Response
    {
        $circuits = $circuitRepository->findAll();

        return $this->render('circuit/index.html.twig', [
            'circuits'=>$circuits
        ]);

    }


    #[Route('/circuit/add/', name: 'app_add_circuit')]
    public function addCircuit(Request $request, EntityManagerInterface $entityManager)
    {
        $circuit = new Circuit();
        $form = $this->createForm(CircuitType::class, $circuit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($circuit);
            $entityManager->flush();
        }

        return $this->render('circuit/addCircuit.html.twig', [
            'form'=>$form->createView()
        ]);
    }


}
