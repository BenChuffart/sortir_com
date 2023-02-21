<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TripController extends AbstractController
{
    /**
     * @Route("/trip", name="app_view")
     */
    public function view (TripRepository $tripRepository): Response
    {
        $trip = $tripRepository -> findAll();

        return $this->render('trip/index.html.twig', [
            'trip' => $trip,
        ]);
          
    }

    /**
     * @Route("/trip", name="app_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trip = new Trip();
        $trip -> setStartDateTime(new \DateTime());
        $tripForm = $this -> createForm(TripType::class, $trip);

        // Permet de récupérer et d'insérer les données récupérées
        $tripForm ->handleRequest($request);

        if($tripForm -> isSubmitted && $tripForm -> isValid())
        {
            $entityManager -> persist($trip);
            $entityManager -> flush();

            $this -> addFlash('success', 'Bien jouer !');
            return $this -> redirectToRoute('main_home');
        }


        return $this->render('trip/create.html.twig', [
            'tripForm' => $tripForm -> createView(),
        ]);
    }

    /**
     * @Route("/trip", name="app_delete")
     */
    public function delete(): Response
    {
        return $this->render('trip/index.html.twig', [
            'controller_name' => 'TripController',
        ]);
    }
}
