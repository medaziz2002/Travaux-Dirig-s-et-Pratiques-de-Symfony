<?php

namespace App\Controller;

use App\Service\Mastermind;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MastermindController extends AbstractController
{
    #[Route('/master', name: 'app_mastermind')]
    public function game(Request $request, SessionInterface $session)
    {
        if (!$session->has('mastermind')) {
            $session->set('mastermind', new Mastermind());
        }

        $game = $session->get('mastermind');

        $proposition = $request->get('proposition');
        $results = null;

        if ($proposition) {
            $guess = str_split($proposition);
            $results = $game->checkGuess($guess);

            if ($results['bienPlaces'] == 4) {
                $this->addFlash('success', 'GAGNÉ ! Le code était : ' . implode('', $game->getCode()));
                $game->reset();
            }
        }

        return $this->render('mastermind/game.html.twig', [
            'history' => $game->getHistory(),
            'results' => $results
        ]);
    }
}
