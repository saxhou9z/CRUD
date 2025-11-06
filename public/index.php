<?php
//error_log("page debut");
session_start();
include_once "../vendor/autoload.php";
include_once "../bootstrap.php";
use Slim\Factory\AppFactory; 
use Slim\Http\Request;

$app = AppFactory::create();
$boissonControleur = new \App\Controleur\controleur_boisson($entityManager);
$categorieControleur = new \App\Controleur\Controleur_Categorie($entityManager);


$app->get('/boisson', [$boissonControleur, 'Accueil']);
$app->get('/boisson/creation', [$boissonControleur, 'Creation']);
$app->post('/boisson/creer', [$boissonControleur, 'Creer']);
$app->get('/boisson/suppression/{idBoisson}', [$boissonControleur, 'Suppression']);
$app->get('/boisson/editer/{idBoisson}', [$boissonControleur, 'Editer']);
$app->get('/boisson/modifier/{idBoisson}', [$boissonControleur, 'Modifier']);

$app->get('/categorie', [$categorieControleur, 'Accueil']);
$app->get('/categorie/creation', [$categorieControleur, 'Creation']);
$app->post('/categorie/creer', [$categorieControleur, 'Creer']);
$app->post('/categorie/suppression/{idCategorie}', [$categorieControleur, 'Suppression']);
$app->get('/categorie/editer/{idCategorie}', [$categorieControleur, 'Editer']);
$app->post('/categorie/modifier/{idCategorie}', [$categorieControleur, 'Modifier']);
$app->get('/categorie/selection', [$categorieControleur, 'Selection']);
$app->post('/categorie/supprimer', [$categorieControleur, 'Suppression']);
$app->run();