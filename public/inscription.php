<?php

require '../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: deconnection.php"); // Redirige vers deconnexion.php si l'utilisateur n'est pas identifié
    exit();
}

// Chargement de Twig
$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader);

$pageActive = 'inscription';

require_once('connect.php');


// Récupération des données pour les menus déroulants
$sql = "SELECT raison_sociale, nom_contact, num_entreprise FROM `entreprise`";
$query = $db->prepare($sql);
$query->execute();
$entreprises = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT nom_etudiant, prenom_etudiant, num_etudiant FROM `etudiant`";
$query = $db->prepare($sql);
$query->execute();
$etudiants = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT nom_prof, prenom_prof, num_prof FROM `professeur`";
$query = $db->prepare($sql);
$query->execute();
$professeurs = $query->fetchAll(PDO::FETCH_ASSOC);

$typesStage = ["Aucun", "Stage", "Alternance"];



// Affichage du template inscription.html.twig
echo $twig->render('inscription.html.twig', [
    'entreprises' => $entreprises,
    'etudiants' => $etudiants,
    'professeurs' => $professeurs,
    'typesStage' => $typesStage,
    'pageActive' => $pageActive,
]);

require_once('close.php');
