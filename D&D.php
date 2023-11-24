<?php
// FICHIERS A IMPORTER
require_once('config.php');
require_once('D&D-DAO.php');
require_once('D&D-Game.php');
require_once('D&D-Personnage.php');
require_once('D&D-Salle.php');

// MISE EN PLACE DE LA CONNEXION A LA BASE DE DONNEES
$connexion = new PDO("mysql:host={$config['hote']};port={$config['port']};dbname={$config['nomDeLaBase']}", $config['utilisateur'], $config['motDePasse']);

// CONNEXION A LA BASE DE DONNEES
$ddDAO = new DD_DAO($connexion);

// CREER LE JEU
$ddGame = new DD_Game($ddDAO);

// DEMARRE LA PARTIE
$ddGame->initGame($ddDAO);
