<?php
require_once('config.php');
require_once('D&D-DAO.php');
require_once('D&D-Game.php');
require_once('D&D-Personnage.php');
require_once('D&D-Salle.php');
$connexion = new PDO("mysql:host={$config['hote']};port={$config['port']};dbname={$config['nomDeLaBase']}", $config['utilisateur'], $config['motDePasse']);

$ddDAO = new DD_DAO($connexion);
$ddGame = new DD_Game($ddDAO);
$ddGame->initGame($ddDAO);
