<?php

namespace App\Service;

use App\Entity\Trip;
use App\Repository\StatusRepository;
use App\Repository\TripRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class StatusService
{

    private TripRepository $tripRepository ;
    private EntityManagerInterface $em;
    private StatusRepository $statusRepository;

    public function __construct(TripRepository $tripRepository, EntityManagerInterface $em, StatusRepository $statusRepository)
    {
        $this -> tripRepository = $tripRepository;
        $this -> em = $em;
        $this -> statusRepository = $statusRepository;

    }


    public function statusOfTrip() :void 
    {
        $trips = $this ->  tripRepository -> allTripOpen();
        $status = $this -> statusRepository -> findOneBy(['label'=> 'Ouverte']);
        foreach($trips as $trip)
        {
            $trip -> setStatut($status);
            $this -> em -> persist($trip);
        }

        $trips = $this->  tripRepository -> allTripClose();
        $status = $this -> statusRepository -> findOneBy(['label'=> 'Clôturée']);
        foreach($trips as $trip)
        {
            $trip -> setStatut($status);
            $this -> em -> persist($trip);
        }

        $trips = $this->  tripRepository -> allTripNow();
        $status = $this -> statusRepository -> findOneBy(['label'=> 'Activité en cours']);
        foreach($trips as $trip)
        {
            $trip -> setStatut($status);
            $this -> em -> persist($trip);
        }


        $trips = $this->  tripRepository -> allTripFail();
        $status = $this -> statusRepository -> findOneBy(['label'=> 'Annulée']);
        foreach($trips as $trip)
        {
            $trip -> setStatut($status);
            $this -> em -> persist($trip);
        }

        $trips = $this->  tripRepository -> allTripFinish();
        $status = $this -> statusRepository -> findOneBy(['label'=> 'Activité passée']);
        foreach($trips as $trip)
        {
            $trip -> setStatut($status);
            $this -> em -> persist($trip);
        }

       flush();

       

    }

}
