<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php'; // contient $entityManager

use Slim\Factory\AppFactory;

// Import des contrôleurs
use App\Controleur\Controleur_Boisson;
use App\Controleur\Controleur_Categorie;

// Crée l’application Slim
$app = AppFactory::create();

// Instancie les contrôleurs avec EntityManager
$boissonControleur   = new Controleur_Boisson($entityManager);
$categorieControleur = new Controleur_Categorie($entityManager);

// ===== ROUTES BOISSON =====
$app->get('/boisson', [$boissonControleur, 'Accueil']);
$app->get('/boisson/creation/{idCategorie}', [$boissonControleur, 'Creation']);
$app->post('/boisson/creer', [$boissonControleur, 'Creer']);
$app->post('/boisson/supprimer', [$boissonControleur, 'Suppression']);
$app->get('/boisson/selection', [$boissonControleur, 'Selection']);
$app->get('/boisson/editer/{idBoisson}', [$boissonControleur, 'Editer']);
$app->post('/boisson/modifier/{idBoisson}', [$boissonControleur, 'Modifier']); // POST pour modifier

// ===== ROUTES CATEGORIE =====
$app->get('/categorie', [$categorieControleur, 'Accueil']);
$app->get('/categorie/creation', [$categorieControleur, 'Creation']);
$app->post('/categorie/creer', [$categorieControleur, 'Creer']);
$app->post('/categorie/supprimer', [$categorieControleur, 'Suppression']); // formulaire suppression
$app->get('/categorie/selection', [$categorieControleur, 'Selection']);
$app->get('/categorie/editer/{idCategorie}', [$categorieControleur, 'Editer']);
$app->post('/categorie/modifier/{idCategorie}', [$categorieControleur, 'Modifier']);

// ===== Démarre l’application =====
$app->run();
