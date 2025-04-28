<?php
require '../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: deconnection.php"); // Redirige vers deconnexion.php si l'utilisateur n'est pas identifié
    exit();
}

//Chargement de Twig
$loader = new FilesystemLoader('../templates'); 
$twig = new Environment($loader);

$pageActive = 'stagiaire';

require_once('connect.php'); 

// Vérifiez si l'ID du stagiaire est passé dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête pour récupérer les détails du stagiaire
    $sql = "
        SELECT s.num_stage, s.debut_stage, s.fin_stage, s.type_stage, s.desc_projet, s.observation_stage,
               e.num_etudiant, e.nom_etudiant, e.prenom_etudiant, e.annee_obtention, e.login, e.mdp, e.num_classe, e.en_activite
        FROM stage s
        JOIN etudiant e ON s.num_etudiant = e.num_etudiant
        WHERE s.num_stage = :id
    ";

    $query = $db->prepare($sql);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();

    // Récupérer les détails du stagiaire
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // Si le stagiaire existe, on affiche les détails
    if ($user) {

        // Passer les données à Twig pour l'affichage
        echo $twig->render('details.html.twig', ['user' => $user]);
    } else {
        echo "Aucun stagiaire trouvé.";
    }
} else {
    echo "ID stagiaire non spécifié.";
}

require_once 'close.php'; // Fermer la connexion à la base de données
?>
