<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\ContractEnum;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    //TODO : add uniqaue email validation to avoid doctrine exception

    #[Route('/', name: 'app_dispatch')]
    public function signIn(): Response
    {
        return $this->render('registration/index.html.twig', [
            'controller_name' => 'RegistrationController',
        ]);
    }

    #[Route('/sign-in', name: 'app_sign_in')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $newUser = new User();
        $form = $this->createForm(RegistrationType::class, $newUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setStatus(ContractEnum::CDI);
            $user->setRole('ROLE_EMPLOYEE');
            try {
                $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
                $em->persist($user);
                $em->flush();
            } catch (Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création du compte. ' . $e->getMessage());
                return $this->redirectToRoute('app_registration');
            }
        }

        return $this->render('registration/sign-in.html.twig', [
            'controller_name' => 'RegistrationController',
            'form' => $form,
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response {
        return $this->render('registration/login.html.twig', [
            'controller_name' => 'RegistrationController',
        ]);
    }
}
