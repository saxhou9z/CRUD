<?php

namespace App\Controleur;

use App\Entity\Categorie;
use App\Entity\Boisson;
use App\Vue\Vue_BasDePage;
use App\Vue\Vue_EditerCategorie;
use App\Vue\Vue_Entete;
use App\Vue\Vue_ListeCategorie;
use App\Vue\Vue_CreationCategorie;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\ORM\EntityManager;

class Controleur_Categorie
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /** 🏠 Affiche la liste des catégories */
    public function Accueil(Request $request, Response $response, array $args): Response
    {
        $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();

        $strHtml = Vue_Entete::donneHTML() .
                   Vue_ListeCategorie::donneHTML($listeCategorie) .
                   Vue_BasDePage::donneHTML();

        $response->getBody()->write($strHtml);
        return $response;
    }

    /** ➕ Formulaire de création */
    public function Creation(Request $request, Response $response, array $args): Response
    {
        $strHtml = Vue_Entete::donneHTML() .
                   Vue_CreationCategorie::donneHTML() .
                   Vue_BasDePage::donneHTML();

        $response->getBody()->write($strHtml);
        return $response;
    }

    /** 💾 Enregistre une nouvelle catégorie */
    public function Creer(Request $request, Response $response, array $args): Response
    {
        $libelle = $_REQUEST['libelle'] ?? '';
        if (trim($libelle) === '') {
            $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
            $msgErreur = "⚠️ Le libellé ne peut pas être vide.";
            $strHtml = Vue_Entete::donneHTML() .
                       Vue_ListeCategorie::donneHTML($listeCategorie, $msgErreur) .
                       Vue_BasDePage::donneHTML();
            $response->getBody()->write($strHtml);
            return $response;
        }

        $nvCategorie = new Categorie($libelle);
        $this->entityManager->persist($nvCategorie);
        $this->entityManager->flush();

        return $response
            ->withHeader('Location', '/categorie')
            ->withStatus(302);
    }

    /** 🗑️ Suppression d’une catégorie */
    public function Suppression(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $idCategorie = $data['categorie'] ?? null;

        $msgErreur = "";
        if ($idCategorie) {
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($idCategorie);

            if (!$categorie) {
                $msgErreur = "❌ Catégorie introuvable.";
            } else {
                $boissons = $this->entityManager
                    ->getRepository(Boisson::class)
                    ->findBy(['categorie' => $categorie]);

                if ($boissons) {
                    $msgErreur = "⚠️ Suppression impossible : des boissons sont associées à cette catégorie.";
                } else {
                    $this->entityManager->remove($categorie);
                    $this->entityManager->flush();
                }
            }
        } else {
            $msgErreur = "⚠️ Aucune catégorie sélectionnée.";
        }

        // Recharge la liste après suppression ou erreur
        $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();

        $strHtml = Vue_Entete::donneHTML() .
                   Vue_ListeCategorie::donneHTML($listeCategorie, $msgErreur) .
                   Vue_BasDePage::donneHTML();

        $response->getBody()->write($strHtml);
        return $response;
    }

    /** ✏️ Formulaire d’édition */
    public function Editer(Request $request, Response $response, array $args): Response
    {
        $idCategorie = $args['idCategorie'] ?? null;
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($idCategorie);

        if (!$categorie) {
            $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
            $msgErreur = "❌ Catégorie introuvable.";
            $strHtml = Vue_Entete::donneHTML() .
                       Vue_ListeCategorie::donneHTML($listeCategorie, $msgErreur) .
                       Vue_BasDePage::donneHTML();
        } else {
            $strHtml = Vue_Entete::donneHTML() .
                       Vue_EditerCategorie::donneHTML($categorie) .
                       Vue_BasDePage::donneHTML();
        }

        $response->getBody()->write($strHtml);
        return $response;
    }

    /** 🪶 Enregistre la modification */
    public function Modifier(Request $request, Response $response, array $args): Response
    {
        $idCategorie = $args['idCategorie'] ?? null;
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($idCategorie);

        if ($categorie) {
            $libelle = $_REQUEST['libelle'] ?? '';
            $categorie->setLibelle($libelle);
            $this->entityManager->flush();
        }

        return $response
            ->withHeader('Location', '/categorie')
            ->withStatus(302);
    }

    /** 🔍 Affiche les boissons d’une catégorie */
    public function Selection(Request $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();
        $idCategorie = $params['categorie'] ?? null;

        if (empty($idCategorie)) {
            $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
            $msgErreur = "⚠️ Vous devez sélectionner une catégorie.";
            $strHtml = Vue_Entete::donneHTML() .
                       Vue_ListeCategorie::donneHTML($listeCategorie, $msgErreur) .
                       Vue_BasDePage::donneHTML();

            $response->getBody()->write($strHtml);
            return $response;
        }

        $categorie = $this->entityManager->getRepository(Categorie::class)->find($idCategorie);
        if (!$categorie) {
            $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
            $msgErreur = "❌ Catégorie non trouvée.";
            $strHtml = Vue_Entete::donneHTML() .
                       Vue_ListeCategorie::donneHTML($listeCategorie, $msgErreur) .
                       Vue_BasDePage::donneHTML();

            $response->getBody()->write($strHtml);
            return $response;
        }

        $boissons = $this->entityManager
            ->getRepository(Boisson::class)
            ->findBy(['categorie' => $categorie]);

        $html = Vue_Entete::donneHTML();
        $html .= "<h2>Boissons de la catégorie : " . htmlspecialchars($categorie->getLibelle()) . "</h2>";

        if ($boissons) {
            $html .= "<ul>";
            foreach ($boissons as $boisson) {
                $html .= "<li>" . htmlspecialchars($boisson->getNom()) . "</li>";
            }
            $html .= "</ul>";
        } else {
            $html .= "<p>Aucune boisson dans cette catégorie.</p>";
        }

        $html .= "<a href='/categorie'>⬅ Retour à la liste des catégories</a>";
        $html .= Vue_BasDePage::donneHTML();

        $response->getBody()->write($html);
        return $response;
    }
}
