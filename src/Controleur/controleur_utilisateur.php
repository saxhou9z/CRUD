<?php

namespace App\Controleur;

use App\Entity\Categorie;
use App\Vue\Vue_BasDePage;
use App\Vue\Vue_EditerCategorie;
use App\Vue\Vue_Entete;
use App\Vue\Vue_ListeCategorie;
use App\Vue\Vue_CreationCategorie;
use AppendIterator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\ORM\EntityManager;

class controleur_utilisateur
{
    private EntityManager $entityManager;
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function Accueil(Request $request, Response $response, array $args): Response
    {
        $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();

        //Il a cliqué sur changer Mot de passe. Cas pas fini
        $strHtml = Vue_Entete::donneHTML() .
            Vue_ListeCategorie::donneHTML($listeCategorie) .
            Vue_BasDePage::donneHTML();


        $response->getBody()->write($strHtml);
        return $response;
    }
}