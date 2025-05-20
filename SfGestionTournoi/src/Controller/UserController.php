<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UserType;
use App\Service\FlashMessageHelperInterface;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    #[Route('/inscription', name: 'app_inscription', methods: ["GET", "POST"])]
    public function inscription(Request $request, EntityManagerInterface $entityManager, FlashMessageHelperInterface $flashMessageHelper, UserManagerInterface $utilisateurManager,
    ): Response
    {
        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_home');
        }
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UserType::class, $utilisateur,
            [
                'method' => 'POST',
                'action' => $this->generateURL('app_inscription')
            ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $utilisateurManager->proccessNewUser(
                $utilisateur,
                $form->get('plainPassword')->getData(),
            );

            $entityManager->persist($utilisateur);
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur a été enregistrée avec succès.');

            return $this->redirectToRoute('app_home');
        }

        $flashMessageHelper->addFormErrorsAsFlash($form);

        return $this->render("user/inscription.html.twig",
            [
                "form" => $form,
            ]);
    }

    #[Route('/connexion', name: 'app_connexion', methods: ['GET', 'POST'])]
    public function connexion(AuthenticationUtils $authenticationUtils) : Response {
        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/connexion.html.twig');
    }

    #[Route('/deconnexion', name: 'app_deconnexion', methods: ['POST'])]
    public function deconnexion() : never {
        throw new \Exception("Cette route ne doit pas être appelée");
    }


}