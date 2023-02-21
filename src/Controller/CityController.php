<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CityController extends AbstractController
{
    /**
     * @Route("/admin/city/create", name="city_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $city = new City();
        $cityForm = $this -> createForm(CityType::class,$city);
        $cityForm -> handleRequest($request);

        if($cityForm -> isSubmitted()&& $cityForm -> isValid())
        {
            $entityManager -> persist($city);
            $entityManager -> flush();
            $this -> addFlash('success', ' New city created !');
            return $this -> redirectToRoute('main_home');

        }

        return $this->render('admin/city/create.html.twig', [
            'cityForm' => $cityForm -> createView(),
        ]);
    }


     /**
     * @Route("/city", name="city_list")
     */
    public function list(CityRepository $cityRepository): Response
    {
        $cities = $cityRepository -> findAll();

        return $this->render('city/list.html.twig', [
            'cities' => $cities
        ]);
    }
}
