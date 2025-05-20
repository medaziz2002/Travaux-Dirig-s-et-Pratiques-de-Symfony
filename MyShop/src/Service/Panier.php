<?php

namespace App\Service;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier
{
    private SessionInterface $session;

    private const PANIER_KEY = 'panier';

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();

        if (!$this->session->has(self::PANIER_KEY)) {
            $this->session->set(self::PANIER_KEY, []);
        }
    }

    public function setLigne(Article $article, int $quantite): void
    {
        $panier = $this->session->get(self::PANIER_KEY, []);
        $articleId = $article->getId();

        if (isset($panier[$articleId])) {
            $nouvelleQuantite = $panier[$articleId]['quantite'] + $quantite;
            if ($nouvelleQuantite < 1) {
                unset($panier[$articleId]);
            } else {
                $panier[$articleId]['quantite'] = $nouvelleQuantite;
            }
        } else {
            if ($quantite > 0) {
                $panier[$articleId] = [
                    'article' => $article,
                    'quantite' => $quantite,
                ];
            }
        }

        $this->session->set(self::PANIER_KEY, $panier);
    }



    public function getLignes(): array
    {
        return $this->session->get(self::PANIER_KEY, []);
    }

    public function clearPanier(): void
    {
        $this->session->remove(self::PANIER_KEY);
    }
}