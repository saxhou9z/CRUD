<?php
namespace App\Controleur;

use App\Entity\Categorie;
use App\Entity\Boisson;
use App\Vue\Vue_Entete;
use App\Vue\Vue_BasDePage;
use App\Vue\Vue_ListeCategorie;
use App\Vue\Vue_CreationCategorie;
use App\Vue\Vue_EditerCategorie;
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

    // Liste toutes les catégories
    public function Accueil(Request $request, Response $response, array $args): Response
    {
        $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
        $html = Vue_Entete::donneHTML() .
                Vue_ListeCategorie::donneHTML($listeCategorie) .
                Vue_BasDePage::donneHTML();

        $response->getBody()->write($html);
        return $response;
    }

    // Formulaire création
    public function Creation(Request $request, Response $response, array $args): Response
    {
        $html = Vue_Entete::donneHTML() .
                Vue_CreationCategorie::donneHTML() .
                Vue_BasDePage::donneHTML();

        $response->getBody()->write($html);
        return $response;
    }

    // Créer catégorie
    public function Creer(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $libelle = trim($data['libelle'] ?? '');
        if ($libelle === '') {
            $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
            $msgErreur = "⚠️ Le libellé ne peut pas être vide.";
            $html = Vue_Entete::donneHTML() .
                    Vue_ListeCategorie::donneHTML($listeCategorie, $msgErreur) .
                    Vue_BasDePage::donneHTML();
            $response->getBody()->write($html);
            return $response;
        }

        $categorie = new Categorie($libelle);
        $this->entityManager->persist($categorie);
        $this->entityManager->flush();

        return $response->withHeader('Location', '/categorie')->withStatus(302);
    }

    // Suppression catégorie
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
                $boissons = $this->entityManager->getRepository(Boisson::class)->findBy(['categorie' => $categorie]);
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

        $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
        $html = Vue_Entete::donneHTML() .
                Vue_ListeCategorie::donneHTML($listeCategorie, $msgErreur) .
                Vue_BasDePage::donneHTML();
        $response->getBody()->write($html);
        return $response;
    }

    // Formulaire édition
    public function Editer(Request $request, Response $response, array $args): Response
    {
        $idCategorie = $args['idCategorie'] ?? null;
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($idCategorie);

        if (!$categorie) {
            $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
            $msgErreur = "❌ Catégorie introuvable.";
            $html = Vue_Entete::donneHTML() .
                    Vue_ListeCategorie::donneHTML($listeCategorie, $msgErreur) .
                    Vue_BasDePage::donneHTML();
        } else {
            $html = Vue_Entete::donneHTML() .
                    Vue_EditerCategorie::donneHTML($categorie) .
                    Vue_BasDePage::donneHTML();
        }

        $response->getBody()->write($html);
        return $response;
    }

    // Modifier catégorie
    public function Modifier(Request $request, Response $response, array $args): Response
    {
        $idCategorie = $args['idCategorie'] ?? null;
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($idCategorie);
        $data = $request->getParsedBody();

        if ($categorie) {
            $categorie->setLibelle($data['libelle'] ?? $categorie->getLibelle());
            $this->entityManager->flush();
        }

        return $response->withHeader('Location', '/categorie')->withStatus(302);
    }

    // Affiche les boissons d’une catégorie
    public function Selection(Request $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();
        $idCategorie = $params['categorie'] ?? null;

        if (empty($idCategorie)) {
            $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
            $msgErreur = "⚠️ Vous devez sélectionner une catégorie.";
            $html = Vue_Entete::donneHTML() .
                    Vue_ListeCategorie::donneHTML($listeCategorie, $msgErreur) .
                    Vue_BasDePage::donneHTML();
            $response->getBody()->write($html);
            return $response;
        }

        $categorie = $this->entityManager->getRepository(Categorie::class)->find($idCategorie);
        if (!$categorie) {
            $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
            $msgErreur = "❌ Catégorie non trouvée.";
            $html = Vue_Entete::donneHTML() .
                    Vue_ListeCategorie::donneHTML($listeCategorie, $msgErreur) .
                    Vue_BasDePage::donneHTML();
            $response->getBody()->write($html);
            return $response;
        }

        $boissons = $this->entityManager->getRepository(Boisson::class)->findBy(['categorie' => $categorie]);

        // Affichage liste boissons avec boutons Créer / Modifier / Supprimer
        $html = Vue_Entete::donneHTML() .
        \App\Vue\Vue_ListeBoisson::donneHTML($boissons, "", $categorie) .
        Vue_BasDePage::donneHTML();


        $response->getBody()->write($html);
        return $response;
    }
}
