<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Form\SupprTournamentType;
use App\Form\TournoiType;
use App\Repository\EvenementRepository;
use App\Repository\TournoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TournoiController extends AbstractController{

    #[Route('/home', name: 'app_home')]
    public function getEvents(EvenementRepository $evenementRepository): Response
    {
        $evenements = $evenementRepository->findAll();

        return $this->render('evenement/home.html.twig', [
            'controller_name' => 'TournoiController',
            'evenements' => $evenements
        ]);
    }

    #[Route('/tournament/{id}', name: 'app_single_tournament')]
    public function getSingleTournament(TournoiRepository $tournoiRepository, int $id): Response
    {
        $tournoi = $tournoiRepository->find($id);
        return $this->render('tournoi/single.html.twig', [
            'tournoi' => $tournoi
        ]);
    }

    #[Route('/tournoi/create/{id}', name: 'app_create_tournament',  methods: ['POST', 'GET'])]
    public function addTournament(Request $request, TournoiRepository $tournoiRepository, EvenementRepository $evenementRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $tournoi, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_create_tournament', ['id' => $id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $evenement = $evenementRepository->find($id);
            $tournoi->setEvId($evenement);
            $entityManager->persist($tournoi);
            $entityManager->flush();
            $this->addFlash('success', 'Tournoi créé');
            return $this->redirectToRoute('app_single_event', ['id' => $id]);
        }
        return $this->render('tournoi/addForm.html.twig', [
            'tournois' => $tournoiRepository->findAll(),
            'form' => $form
        ]);
    }

    #[Route('/event/{id}/suppr-tournament', name: 'app_suppr_tournament', methods: ['GET', 'POST'])]
    public function supprTournament(Request $request, TournoiRepository $tournoiRepository, EntityManagerInterface $entityManager, int $id): Response {
        $tournois = $tournoiRepository->findBy(array('ev_id' => $id));
        $tournamentChoices = array_combine(
            array_map(fn($tournoi) => $tournoi->getNom(), $tournois),
            array_map(fn($tournoi) => $tournoi->getId(), $tournois)
        );

        $form = $this->createForm(SupprTournamentType::class, null, ['tournaments' => $tournamentChoices]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedTournamentIds = $form->get('tournament_ids')->getData();
            foreach ($selectedTournamentIds as $ids) {
                $tournoi = $tournoiRepository->find($ids);
                if ($tournoi) {
                    $entityManager->remove($tournoi);
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Les tournois sélectionnés ont été supprimés avec succès.');
            return $this->redirectToRoute('app_single_event', ['id' => $id]);
        }

        return $this->render('tournoi/supprTournament.html.twig', [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }
}
