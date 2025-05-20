<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\FormulaireType;
use App\Form\SupprEventType;
use App\Repository\EvenementRepository;
use App\Repository\TournoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EvenementController extends AbstractController{

    #[Route('/home', name: 'app_home')]
    public function getEvents(EvenementRepository $evenementRepository): Response
    {
        $evenements = $evenementRepository->findAll();

        return $this->render('evenement/home.html.twig', [
            'controller_name' => 'EvenementController',
            'evenements' => $evenements
        ]);
    }

    #[Route('/suppr-event', name: 'app_suppr_event', methods: ['GET', 'POST'])]
    public function supprEvent(Request $request, EvenementRepository $evenementRepository, EntityManagerInterface $entityManager): Response
    {
        $evenements = $evenementRepository->findAll();
        $res = "";

        $eventChoices = array_combine(
            array_map(fn($event) => $event->getNom(), $evenements),
            array_map(fn($event) => $event->getId(), $evenements)
        );
        $form = $this->createForm(SupprEventType::class, null, ['events' => $eventChoices]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedEventIds = $form->get('event_ids')->getData();
            foreach ($selectedEventIds as $id) {
                $event = $evenementRepository->find($id);
                $res = $res . $event->getNom() . ", ";
                if ($event) {
                    $entityManager->remove($event);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Les événements ' . $res . 'ont été supprimés avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('evenement/supprEvent.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/event/{id}', name: 'app_single_event')]
    public function getSingleEvent(EvenementRepository $evenementRepository, int $id): Response
    {
        $evenement = $evenementRepository->find($id);
        $tournois = $evenement->getTournois();

        return $this->render('evenement/single.html.twig', [
            'controller_name' => 'EvenementController',
            'evenement' => $evenement,
            'tournois' => $tournois
        ]);
    }

    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        return $this->render('home/addForm.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }

    #[Route('/evenenements/create', name: 'app_create_evenement', methods: ['POST', 'GET'])]
    public function createEvent(Request $request, EvenementRepository $evenementRepository, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(FormulaireType::class, $evenement,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('app_create_evenement'),
            ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();
            $this->addFlash('success', 'Evenement créé');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('evenement/addForm.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'form' => $form
        ]);
    }

}
