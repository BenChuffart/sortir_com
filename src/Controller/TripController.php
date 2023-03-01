<?php

namespace App\Controller;

use App\Entity\Trip;
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
use Symfony\Component\HttpFoundation\Request;

class TripController extends AbstractController
{
    /**
     * @Route("/trip/create", name="trip_create")
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
     * @Route("/trip", name="trip_view")
     */
    public function view (TripRepository $tripRepository, Request $request): Response
    {
        
        $data = new Filters();
        $data -> campus = $this -> getUser()-> getCampus();
        $filterform = $this -> createform(FiltersType::class, $data);
        $filterform -> handleRequest($request);
        $search = $tripRepository -> findTrip($data,$this-> getUser());
        
    
        return $this->render('trip/view.html.twig', [
            'filterform' => $filterform -> createView(),
            'trips' => $search
        ]);
          
    }

    /**
     * @Route("/trip/editTrip", name="trip_editTrip")
     */
    public function editTrip(Trip $trip,User $user)
    {
        if($this -> isGranted('POST_EDIT',$trip))
        {
            
            return $this -> redirectToRoute('trip_view');
        }
        $this -> addFlash('Denied', 'Accès refusé !');
        return $this->render('trip/view.html.twig');
    }

     /**
     * @Route("/trip/deletTrip", name="trip_deleteTrip")
     */
    public function deleteTrip(Trip $trip,User $user)
    {
        if($this -> isGranted('POST_DELETE',$trip))
        {
            
            return $this -> redirectToRoute('trip_view');
        }
        $this -> addFlash('Denied', 'Accès refusé !');
        return $this->render('trip/view.html.twig');
    }

    /**
     * @Route("/trip/viewATrip", name="trip_viewATrip")
     */
    public function viewATrip(Trip $trip,User $user)
    {
        if($this -> isGranted('POST_EDIT',$trip))
        {
            
            return $this -> redirectToRoute('trip_view');
        }
        $this -> addFlash('Denied', 'Accès refusé !');
        return $this->render('trip/view.html.twig');
    }
    
        /**
     * @Route("/show_trip/{id}", name="_tripShow")
     * @param int $id
     * @param TripRepository $tripRepository
     * @return Response
     */
    public function showTrip (int $id, TripRepository $tripRepository): Response
    {
        $showTrip = $tripRepository->find($id);

        return $this->render('trip/showTrip.html.twig', [
            "trip" => $showTrip
        ]);
    }
    
}
