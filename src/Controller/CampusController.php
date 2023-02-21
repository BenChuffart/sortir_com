<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CampusController extends AbstractController
{
    /**
     * @Route("/campus", name="campus_list")
     */
    public function list(CampusRepository $campusRepository): Response
    {
        $campuses = $campusRepository -> findAll();
        return $this->render('campus/list.html.twig', [
            'campuses' => $campuses,
        ]);
    }

     /**
     * @Route("admin/campus/create", name="campus_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();
        $campusForm = $this -> createForm(CampusType::class,$campus);

        if($campusForm -> isSubmitted() && $campusForm -> isValid())
        {
            $entityManager -> persist($campus);
            $entityManager -> flush();

            $this -> addFlash('success', 'New campus create !');
            return $this -> redirectToRoute('main_home');
        }

        return $this->render('admin/campus/create.html.twig', [
            'controller_name' => 'CampusController',
        ]);
    }
}
