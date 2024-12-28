<?php
$host = 'localhost'; 
$dbname = 'bdd_geststages'; 
$username = 'root'; 
$password = ''; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // gestion des erreurs
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // mode de récupération
    echo "connexion réussie à la base de données 'bdd_geststages' !";
} catch (PDOException $e) {
    die("erreur : la connexion à la base de données a échoué. " . $e->getMessage());
}
?>

