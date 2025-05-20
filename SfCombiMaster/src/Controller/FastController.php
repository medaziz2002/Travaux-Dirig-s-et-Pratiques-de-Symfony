<?php

namespace App\Controller;

use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FastController extends AbstractController
{
    #[Route('/fact', name: 'app_home')]
    public function index(Request $request): Response
    {
        $n_fact = $request->query->get('n_fact');
        $n_comb = $request->query->get('n_comb');
        $p = $request->query->get('p');
        $result = null;

        if (!is_null($n_fact)) {
            $result = Utils::factorielle((int)$n_fact);
            return $this->render('factocombi.html.twig', [
                'n_fact' => $n_fact,
                'result' => $result,
            ]);
        }
        else if (!is_null($n_comb) && !is_null($p)) {
            $result = Utils::combinaison((int)$n_comb, (int)$p);
            return $this->render('factocombi.html.twig', [
                'n_comb' => $n_comb,
                'p' => $p,
                'result' => $result,
            ]);
        }
        else {
            return $this->render('base.html.twig');
        }
    }
}
