<?php

require '../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: deconnection.php"); // Redirige vers deconnexion.php si l'utilisateur n'est pas identifié
    exit();
}

$loader = new FilesystemLoader('../templates'); 
$twig = new Environment($loader);

try{
    // Connexion à la bdd
    $db = new PDO('mysql:host=localhost;dbname=bdd_geststages', 'root','');
    $db->exec('SET NAMES "UTF8"');
} catch (PDOException $e){
    echo 'Erreur : '. $e->getMessage();
    die();
}

$sql = "SELECT nom_classe FROM `classe`";
$query = $db->prepare($sql);
$query->execute();
$classe = $query->fetchAll(PDO::FETCH_ASSOC);

echo $twig->render('ajout_etudiant.html.twig',['classes' => $classe]);