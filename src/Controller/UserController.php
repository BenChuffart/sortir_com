<?php

namespace App\Controller;

use App\Form\EditPasswordType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/users", name="users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="_profile")
     */
    public function showMyProfile(int $id, UserRepository $userRepository): Response
    {
        $userProfile = $userRepository->find($id);

        return $this->render('user/profile.html.twig', [
            "user" => $userProfile
        ]);
    }

    /**
     * @Route("/edit_profile/{id}", name="_editProfile")
     */
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        $this->denyAccessUnlessGranted('PROFILE_EDIT', $user);
        $editForm = $this->createForm(UserEditType::class, $user);

        $editForm->handleRequest($request);
        if($editForm->isSubmitted() && $editForm->isValid()){
            $user = $editForm->getData();

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated !');

            return $this->redirectToRoute("main_home");
        }

        return $this->render('user/edit_profile.html.twig', [
            'editForm' => $editForm->createView()
        ]);
    }

    /**
     * @Route("/edit_password/{id}", name="_editPassword")
     */
    public function changePwd(int $id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $userRepository->find($id);
        $editForm = $this->createForm(EditPasswordType::class, $user);

        $editForm->handleRequest($request);
        if($editForm->isSubmitted() && $editForm->isValid()){
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $editForm->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated !');

            return $this->redirectToRoute('main_home');
        }

        return $this->render('user/edit_password.html.twig', [
            'editForm' => $editForm->createView()
        ]);
    }
}
