<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Data\Filters;
use App\Entity\Campus;
use App\Form\FiltersType;
use App\Repository\CampusRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TripController extends AbstractController
{
    /**
     * @Route("/trip/create", name="trip_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, UserRepository $user): Response
    {
        $trip = new Trip();
        $trip -> setStartDateTime(new \DateTime());
        $tripForm = $this -> createForm(TripType::class, $trip);
        
        // Permet de récupérer et d'insérer les données récupérées
        $tripForm ->handleRequest($request);
        
        if($tripForm -> isSubmitted() && $tripForm -> isValid())
        {
            $trip->setCreator($this->getUser());
            
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
     * @Route("/trip", name="trip_view")
     */
    public function view (TripRepository $tripRepository, Request $request): Response
    {

        $data = new Filters();
        $data -> campus = $this -> getUser()->getCampus();
        $filterform = $this -> createform(FiltersType::class, $data);
        $filterform -> handleRequest($request);
        $search = $tripRepository -> findTrip($data,$this-> getUser());
    
        return $this->render('trip/view.html.twig', [
            'filterform' => $filterform -> createView(),
            'trips' => $search
        ]);
          
    }

}
