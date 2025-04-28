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

// Traitement du formulaire

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['entreprise'], $_POST['etudiant'], $_POST['professeur'], $_POST['debut_stage'], $_POST['fin_stage'], $_POST['type_stage']) 
        && !empty($_POST['entreprise']) && !empty($_POST['etudiant']) && !empty($_POST['professeur']) 
        && !empty($_POST['debut_stage']) && !empty($_POST['fin_stage']) && !empty($_POST['type_stage'])) {

        // Récupération et nettoyage des données
        $entrepriseId = intval($_POST['entreprise']);
        $etudiantId = intval($_POST['etudiant']);
        $professeurId = intval($_POST['professeur']);
        $debut_stage = strip_tags($_POST['debut_stage']);
        $fin_stage = strip_tags($_POST['fin_stage']);
        $type_stage = strip_tags($_POST['type_stage']);
        $desc_projet = !empty($_POST['desc_projet']) ? strip_tags($_POST['desc_projet']) : null;
        $observation = !empty($_POST['observation']) ? strip_tags($_POST['observation']) : null;

        // Requête d'insertion
        $sql = "INSERT INTO `stage` (`debut_stage`, `fin_stage`, `type_stage`, `desc_projet`, `observation_stage`, `num_etudiant`, `num_prof`, `num_entreprise`) 
                VALUES ('2025-01-01','2026-01-01' , 'alternance','cvbn' , ':observation', 4, 4, 2);";

        $query = $db->prepare($sql);

        // Liaison des valeurs
        $query->bindValue(':debut_stage', $debut_stage, PDO::PARAM_STR);
        $query->bindValue(':fin_stage', $fin_stage, PDO::PARAM_STR);
        $query->bindValue(':type_stage', $type_stage, PDO::PARAM_STR);
        $query->bindValue(':desc_projet', $desc_projet, PDO::PARAM_STR);
        $query->bindValue(':observation_stage', $observation, PDO::PARAM_STR);
        $query->bindValue(':num_etudiant', $etudiantId, PDO::PARAM_INT);
        $query->bindValue(':num_prof', $professeurId, PDO::PARAM_INT);
        $query->bindValue(':num_entreprise', $entrepriseId, PDO::PARAM_INT);


        // Redirection pour éviter la soumission multiple du formulaire
        header('Location: inscription.php');

        // Exécution de la requête
        if ($query->execute()) {
            $_SESSION['message'] = "Stagiaire ajouté avec succès !";
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de l'ajout du stagiaire.";
        }

        exit();
    }
}

// Affichage du template inscription.html.twig
echo $twig->render('inscription.html.twig', [
    'message' => $_SESSION['message'] ?? null,
    'error' => $_SESSION['error'] ?? null,
]);

require_once('close.php');