<?php

require '../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: deconnection.php"); // Redirige vers deconnexion.php si l'utilisateur n'est pas identifié
    exit();
}

// Récupération du rôle de l'utilisateur
$role = $_SESSION['role'];

//Chargement de Twig
$loader = new FilesystemLoader('../templates'); 
$twig = new Environment($loader);

// Variable transmise au template 
$pageActive = 'liste_etudiants';

// Connexion à la bdd
require_once('connect.php'); 

// Requête 
$sql = "SELECT 
            e.prenom_etudiant, e.nom_etudiant,
            en.raison_sociale,
            p.prenom_prof, p.nom_prof,
            s.num_stage
        FROM stage s
        JOIN etudiant e ON s.num_etudiant = e.num_etudiant
        JOIN entreprise en ON s.num_entreprise = en.num_entreprise
        JOIN professeur p ON s.num_prof = p.num_prof;

";
$query = $db->prepare($sql); // Préparation de la requête 
$query->execute(); // Exécution la requête 
$result = $query->fetchAll(PDO::FETCH_ASSOC); // Stockage du résultat dans un tableau associatif 

// Fermeture de la connexion à la bd
require_once('close.php'); 


// Affichage du template liste_etudiants.html.twig
echo $twig->render('liste_etudiants.html.twig', [
    'pageActive' => $pageActive,
    'result' => $result, 
    'role' => $role
]);