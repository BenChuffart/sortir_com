<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Trip;
use App\Entity\User;
use App\Form\TripType;
use App\Data\Filters;
use App\Form\FiltersType;
use App\Repository\StatusRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/trips", name="trips")
 */
class TripController extends AbstractController
{
    /**
     * @Route("/create", name="_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, StatusRepository $statusRepository): Response
    {
        $trip = new Trip();
        $trip -> setStartDateTime(new \DateTime());
        $trip -> setDeadline(new \DateTime());
        $tripForm = $this -> createForm(TripType::class, $trip);
        
        // Permet de récupérer et d'insérer les données récupérées
        $tripForm ->handleRequest($request);
        
        if($tripForm -> isSubmitted() && $tripForm -> isValid())
        {
            $status = $statusRepository->find(1);
            $trip -> setStatus($status);

            /** @var User $user */
            $user = $this->getUser();
            $trip->setCreator($user);
            $trip->setCampus($user->getCampus());

            $entityManager -> persist($trip);
            $entityManager -> flush();

            $this -> addFlash('success', 'Bien jouer !');
            return $this -> redirectToRoute('main_home');
        }



        return $this->render('trips/create.html.twig', [
            'tripForm' => $tripForm -> createView(),
        ]);
    }


    /**
     * @Route("/view", name="_view")
     */
    public function view (TripRepository $tripRepository, Request $request): Response
    {
        $filters = new Filters();

        /** @var User $user */
        $user = $this->getUser();

        /** @var Campus $campus */
        $campus = $user->getCampus();

        $filters ->campus = $campus;

        $filterForm = $this -> createform(FiltersType::class, $filters);
        $filterForm -> handleRequest($request);

        $search = $tripRepository -> findTrip($filters, $user);
    
        return $this->render('trips/view.html.twig', [
            'filterForm' => $filterForm -> createView(),
            'trips' => $search
        ]);
          
    }

}
