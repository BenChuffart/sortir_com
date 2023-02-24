<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/users", name="users_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/Profile/{id}", name="Profile")
     */
    public function showMyProfile(int $id, UserRepository $userRepository): Response
    {
        $userProfile = $userRepository->find($id);

        return $this->render('user/userProfile.html.twig', [
            "user" => $userProfile
        ]);
    }

    /**
     * @Route("/modifyProfile/{id}", name="modifyProfile")
     */
    public function editMyProfile(int $id,Request $request,EntityManagerInterface $entityManager): Response
    {
        $editForm = $this->createForm(User::class);

        $editForm->handleRequest($request);
        if($editForm->isSubmitted() && $editForm->isValid()){
            $user = $editForm->getData();

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated !');

            return $this->redirectToRoute('users_Profile');
        }

        return $this->render('editProfile.html.twig', [
            'editForm' => $editForm->createView()
        ]);
    }
}
