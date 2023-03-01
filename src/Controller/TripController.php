<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\User;
use App\Form\TripEditType;
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
     * @Route("/list", name="_list")
     */
    public function showList (TripRepository $tripRepository, Request $request): Response
    {
        $filters = new Filters();

        /** @var User $user */
        $user = $this->getUser();
        $campus = $user->getCampus();

        $filters ->campus = $campus;

        $filterForm = $this -> createform(FiltersType::class, $filters);
        $filterForm -> handleRequest($request);

        $search = $tripRepository -> findTrip($filters, $user);
    
        return $this->render('trips/list.html.twig', [
            'filterForm' => $filterForm -> createView(),
            'trips' => $search
        ]);
          
    }

    /**
     * @Route("/details/{id}", name="_details")
     */
    public function tripDetails (int $id, TripRepository $tripRepository): Response
    {
        $tripDetails = $tripRepository->find($id);

        return $this->render('trips/details.html.twig', [
            'trip' => $tripDetails,
            'users' => $tripDetails->getUsers()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit")
     */
    public function editTrip (int $id, TripRepository $tripRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $trip = $tripRepository->find($id);
        $editForm = $this->createForm(TripEditType::class, $trip);

        $editForm->handleRequest($request);
        if($editForm->isSubmitted() && $editForm->isValid()){
            $trip = $editForm->getData();

            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', 'Trip edited !');
            return $this->redirectToRoute('trips_list');
        }

        return $this->render('trips/edit.html.twig', [
            'editForm' => $editForm->createView(),
            'trip' => $trip
        ]);
    }

}
