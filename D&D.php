<?php
require_once('../config.php');
require_once('D&D-DAO.php');
require_once('D&D-Game.php');
require_once('D&D-Monstre');
require_once('D&D-Personnage');
require_once('D&D-Salle');
$connexion = new PDO("mysql:host={$config['hote']};port={$config['port']};dbname={$config['nomDeLaBase']}", $config['utilisateur'], $config['motDePasse']);





?>