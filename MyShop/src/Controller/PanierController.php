<?php

namespace App\Controller;

use App\Service\Panier;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    private $panier;

    public function __construct(Panier $panier)
    {
        $this->panier = $panier;
    }

    #[Route('/panier', name: 'app_content')]
    public function afficherPanier(ArticleRepository $articleRepository): Response
    {
        $lignes = $this->panier->getLignes();
        $articles = $articleRepository->findAll();

        return $this->render('panier/index.html.twig', [
            'lignes' => $lignes,
            'articles' => $articles,
        ]);
    }

    #[Route('/panier/update/{quantite}/{id}', name: 'app_update')]
    public function ajouterArticle(int $quantite, int $id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException("Article avec l'id $id introuvable.");
        }

        $this->panier->setLigne($article, $quantite);
        $this->addFlash('success', 'Panier modifié');
        return $this->redirectToRoute('app_content');
    }
}
