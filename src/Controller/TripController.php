<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripEditType;
use App\Form\TripType;
use App\Data\Filters;
use App\Entity\User;
use App\Form\FiltersType;
use App\Repository\TripRepository;
use App\Repository\StatusRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/trip", name="trip")
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


        return $this->render('trip/create.html.twig', [
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
        $filters -> campus = $user -> getCampus();
        $filterForm = $this -> createform(FiltersType::class, $filters);
        $filterForm -> handleRequest($request);
        $search = $tripRepository -> findTrip($filters, $user);
        
    
        return $this->render('trip/list.html.twig', [
            'filterForm' => $filterForm -> createView(),
            'trips' => $search
        ]);
          
    }

    /**
     * @Route("/edit/{id}", name="_edit")
     */
    public function edit(Trip $trip,User $user)
    {
        if($this -> isGranted('POST_EDIT',$trip))
        {
            return $this -> redirectToRoute('trip_edit');
        }

        $this -> addFlash('Denied', 'Accès refusé !');
        return $this->render('trip/edit.html.twig', []);
    }

     /**
     * @Route("/delete/{id}", name="_delete")
     */
    public function delete(int $id, ManagerRegistry $managerRegistry, Trip $trip)
    {
        if($this -> isGranted('POST_DELETE',$trip))
        {
            $em = $this-> $managerRegistry-> getRepository(Trip::class);
            $em -> remove($trip);
            $em->flush();
            return $this -> render('trip/delete.html.twig',[
            ]); 
        }
        else{
        $this -> addFlash('Denied', 'Accès refusé !');
        return $this->render('trip/list.html.twig');}
    }

    /**
     * @Route("/details/{id}", name="_details")
     */
    // , methods={"POST"}
    public function details(int $id, TripRepository $tripRepository) :Response
    {
        $trip = $tripRepository-> find($id);
        return $this-> render('trip/details.html.twig',[
            "trip" => $trip
        ]);
    }

     /**
     * @Route("/trip/{id}/register", name="_register", methods={"POST"})
     */
    public function registerTrip(int $id, ManagerRegistry $managerRegistry, Trip $trip) :Response
    {
        /** @var User $user */
        $user = $this-> getUser();
        $trip -> addUser($user);
        $user -> $managerRegistry -> persist($user);
        $this -> $managerRegistry -> flush();
           
            
        return $this -> redirectToRoute('trip_details');
    }

     /**
     * @Route("/trip/{id}/withdraw", name="withdraw")
     */
    public function renounceTrip(int $id,ManagerRegistry $managerRegistry,Trip $trip) :Response
    {
        /** @var User $user */
        $user = $this -> getUser();
        $trip -> removeUser($user);
        $this -> $managerRegistry -> persist($trip);
        $this  -> $managerRegistry -> flush();
       
        return $this -> redirectToRoute('trip_list');
    }
}
