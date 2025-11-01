<?php
use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\repository\DeefyRepository;
require_once 'vendor/autoload.php';
session_start();

// définir la configuration BD 1 fois au démarrage de l'application \
DeefyRepository::setConfig( 'config.ini' );


// chaque fois que l'on a besoin d’appeler une méthode dans une action :
$r = DeefyRepository::getInstance();
//$pl = $r->findPlaylistById(1);



$action = $_GET['action'] ?? '';
$app = new Dispatcher($action);
$app->run();