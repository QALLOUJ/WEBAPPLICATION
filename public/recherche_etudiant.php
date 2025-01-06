<?php
require_once '../vendor/autoload.php';  // Inclure le fichier de Twig
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header("Location: deconnection.php"); // Redirige l'utilisateur s'il n'est pas connecté
    exit();
}

// Récupération du rôle de l'utilisateur
$role = $_SESSION['role'];

// Charger Twig
$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader);

// Connexion à la base de données
require_once 'connect.php';

// Récupérer les critères de recherche
$nom_etudiant = isset($_POST['nom_etudiant']) ? $_POST['nom_etudiant'] : '';
$prenom_etudiant = isset($_POST['prenom_etudiant']) ? $_POST['prenom_etudiant'] : '';
$num_etudiant = isset($_POST['num_etudiant']) ? $_POST['num_etudiant'] : '';
$classe = isset($_POST['classe']) ? $_POST['classe'] : '';

// Requête SQL de base pour récupérer tous les stagiaires si aucun critère de recherche n'est fourni
$sql = "SELECT * FROM etudiant WHERE 1=1";

// Ajouter des conditions de recherche si des critères sont fournis
if ($nom_etudiant != '') {
    $sql .= " AND nom_etudiant LIKE :nom_etudiant";
}
if ($prenom_etudiant != '') {
    $sql .= " AND prenom_etudiant LIKE :prenom_etudiant";
}
if ($num_etudiant != '') {
    $sql .= " AND num_etudiant LIKE :num_etudiant";
}
if ($classe != '') {
    $sql .= " AND classe LIKE :classe";
}

// Préparer la requête
$query = $db->prepare($sql);

// Lier les paramètres de la requête si des critères sont fournis
if ($nom_etudiant != '') {
    $query->bindValue(':nom_etudiant', '%' . $nom_etudiant . '%');
}
if ($prenom_etudiant != '') {
    $query->bindValue(':prenom_etudiant', '%' . $prenom_etudiant . '%');
}
if ($num_etudiant != '') {
    $query->bindValue(':num_etudiant', '%' . $num_etudiant . '%');
}
if ($classe != '') {
    $query->bindValue(':classe', '%' . $classe . '%');
}

// Exécuter la requête
$query->execute();

// Récupérer les résultats de la recherche
$stagiaires = $query->fetchAll(PDO::FETCH_ASSOC);

// Passer les résultats à Twig
echo $twig->render('liste_etudiants.html.twig', [
    'result' => $stagiaires, 
    'nom_etudiant' => $nom_etudiant, 
    'prenom_etudiant' => $prenom_etudiant, 
    'num_etudiant' => $num_etudiant, 
    'classe' => $classe,
    'role' => $role
]);

// Fermer la connexion à la base de données
require_once 'close.php';
?>
