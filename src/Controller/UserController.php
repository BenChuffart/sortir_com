<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/users", name="users_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/myProfile", name="myProfile")
     */
    public function showMyProfile(UserRepository $userRepo): Response
    {
        $user =$userRepo;

        return $this->render('user/myProfile.html.twig', [
        ]);
    }

    /**
     * @Route("/modifyProfile", name="modifyProfile")
     */
    public function modifyMyProfile(): Response
    {
        //todo: aller chercher le profil en BDD

        return $this->render('user/modifyMyProfile.html.twig', [
        ]);
    }

    /**
     * @Route("/userProfile", name="userProfile")
     */
    public function userProfile(): Response
    {
        //todo: aller chercher le profil en BDD

        return $this->render('user/userProfile.html.twig', [
        ]);
    }
}
