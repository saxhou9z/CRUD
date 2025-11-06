<?php
namespace App\Controleur;

use App\Entity\Boisson;
use App\Entity\Categorie;
use App\Vue\Vue_BasDePage;
use App\Vue\Vue_Entete;
use App\Vue\Vue_ListeBoisson;
use App\Vue\Vue_EditerBoisson;
use App\Vue\Vue_CreationBoisson;
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

    /** 🏠 Affiche les boissons d’une catégorie choisie */
    public function Selection(Request $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();
        $idCategorie = $params['categorie'] ?? null;

        if (!$idCategorie) {
            $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
            $msgErreur = "⚠️ Vous devez sélectionner une catégorie.";
            $html = Vue_Entete::donneHTML() .
                    Vue_ListeBoisson::donneHTML($listeCategorie, $msgErreur) .
                    Vue_BasDePage::donneHTML();
            $response->getBody()->write($html);
            return $response;
        }

        $categorie = $this->entityManager->getRepository(Categorie::class)->find($idCategorie);
        if (!$categorie) {
            $msgErreur = "❌ Catégorie non trouvée.";
            $listeCategorie = $this->entityManager->getRepository(Categorie::class)->findAll();
            $html = Vue_Entete::donneHTML() .
                    Vue_ListeBoisson::donneHTML($listeCategorie, $msgErreur) .
                    Vue_BasDePage::donneHTML();
            $response->getBody()->write($html);
            return $response;
        }

        $boissons = $this->entityManager->getRepository(Boisson::class)->findBy(['categorie' => $categorie]);

        $html = Vue_Entete::donneHTML();
        $html .= Vue_ListeBoisson::donneHTML($boissons, "", $categorie);
        $html .= Vue_BasDePage::donneHTML();

        $response->getBody()->write($html);
        return $response;
    }

    /** ➕ Formulaire création boisson */
    public function Creation(Request $request, Response $response, array $args): Response
    {
        $idCategorie = $args['idCategorie'] ?? null;
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($idCategorie);

        $html = Vue_Entete::donneHTML();
        $html .= Vue_CreationBoisson::donneHTML($categorie);
        $html .= Vue_BasDePage::donneHTML();

        $response->getBody()->write($html);
        return $response;
    }

    /** 💾 Crée une nouvelle boisson */
    public function Creer(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $nom = $data['nom'] ?? "";
        $volume = (int)($data['volumeCL'] ?? 0);
        $prix = (float)($data['prix'] ?? 0);
        $idCategorie = $data['categorie'] ?? null;

        $categorie = $this->entityManager->getRepository(Categorie::class)->find($idCategorie);

        if (!$categorie || trim($nom) === "") {
            return $response->withHeader('Location', '/boisson?categorie='.$idCategorie)->withStatus(302);
        }

        $boisson = new Boisson($nom, $volume, $prix, $categorie);
        $this->entityManager->persist($boisson);
        $this->entityManager->flush();

        return $response->withHeader('Location', '/boisson/selection?categorie='.$idCategorie)->withStatus(302);
    }

    /** 🗑 Supprime une boisson */
public function Suppression(Request $request, Response $response, array $args): Response
{
    $data = $request->getParsedBody();
    $idBoisson = $data['idBoisson'] ?? null;

    if ($idBoisson) {
        $boisson = $this->entityManager->getRepository(\App\Entity\Boisson::class)->find($idBoisson);
        if ($boisson) {
            $this->entityManager->remove($boisson);
            $this->entityManager->flush();
        }
    }

    // Redirige vers la sélection de la même catégorie
    $categorieId = $boisson ? $boisson->getCategorie()->getId() : null;
    return $response
        ->withHeader('Location', '/categorie/selection?categorie=' . $categorieId)
        ->withStatus(302);
}


    /** ✏️ Formulaire édition boisson */
    public function Editer(Request $request, Response $response, array $args): Response
    {
        $idBoisson = $args['idBoisson'] ?? null;
        $boisson = $this->entityManager->getRepository(Boisson::class)->find($idBoisson);

        if (!$boisson) {
            return $response->withHeader('Location', '/categorie')->withStatus(302);
        }

        $html = Vue_Entete::donneHTML();
        $html .= Vue_EditerBoisson::donneHTML($boisson);
        $html .= Vue_BasDePage::donneHTML();

        $response->getBody()->write($html);
        return $response;
    }

    /** 🪶 Enregistre modification boisson */
    public function Modifier(Request $request, Response $response, array $args): Response
    {
        $idBoisson = $args['idBoisson'] ?? null;
        $boisson = $this->entityManager->getRepository(Boisson::class)->find($idBoisson);

        if ($boisson) {
            $data = $request->getParsedBody();
            $boisson->setNom($data['nom'] ?? $boisson->getNom());
            $boisson->setVolumeCL((int)($data['volumeCL'] ?? $boisson->getVolumeCL()));
            $boisson->setPrix((float)($data['prix'] ?? $boisson->getPrix()));
            $this->entityManager->flush();
            $idCategorie = $boisson->getCategorie()->getId();
            return $response->withHeader('Location', '/boisson/selection?categorie='.$idCategorie)->withStatus(302);
        }

        return $response->withHeader('Location', '/categorie')->withStatus(302);
    }
}
