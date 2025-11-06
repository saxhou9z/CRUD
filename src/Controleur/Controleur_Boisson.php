<?php

namespace App\Controleur;
use App\Entity\boisson;
use App\Vue\Vue_BasDePage;
use App\Vue\Vue_EditerBoisson;
use App\Vue\Vue_Entete;
use App\Vue\Vue_ListeBoisson;
use App\Vue\Vue_CreationBoisson;
use AppendIterator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\ORM\EntityManager;

class Controleur_Boisson
{
    private EntityManager $entityManager;
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function Accueil(Request $request, Response $response, array $args): Response
    {
        $listeBoisson = $this->entityManager->getRepository(Boisson::class)->findAll();

        //Il a cliqué sur changer Mot de passe. Cas pas fini
        $strHtml = Vue_Entete::donneHTML() .
            Vue_ListeBoisson::donneHTML($listeBoisson) .
            Vue_BasDePage::donneHTML();
        $response->getBody()->write($strHtml);
        return $response;
    }
    
    public function Creation(Request $request, Response $response, array $args): Response
    {
        //Il a cliqué sur changer Mot de passe. Cas pas fini
        $strHtml = Vue_Entete::donneHTML() .
            Vue_CreationBoisson::donneHTML() .
            Vue_BasDePage::donneHTML();


        $response->getBody()->write($strHtml);
        return $response;
    }
}