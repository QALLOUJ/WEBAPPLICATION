<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdd_geststages";

// Crée la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie si la connexion a échoué
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
} else {
    echo "Connexion réussie à la base de données '$dbname' !<br>";
}

// Vérifier si l'ID de l'entreprise est passé dans l'URL
if (isset($_GET['num_entreprise'])) {
    $num_entreprise = $_GET['num_entreprise'];
    echo "ID reçu : $num_entreprise <br>";

    // Supprimer les enregistrements dépendants dans `spec_entreprise`
    $queryDeleteSpec = "DELETE FROM spec_entreprise WHERE num_entreprise = ?";
    $stmtSpec = $conn->prepare($queryDeleteSpec);
    $stmtSpec->bind_param("i", $num_entreprise);
    if (!$stmtSpec->execute()) {
        echo "Erreur lors de la suppression des dépendances : " . $stmtSpec->error;
        $stmtSpec->close();
        $conn->close();
        exit;
    }
    $stmtSpec->close();

    // Supprimer l'entreprise après avoir supprimé les dépendances
    $query = "DELETE FROM entreprise WHERE num_entreprise = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $num_entreprise);

    if (!$stmt->execute()) {
        echo "Erreur MySQLi : " . $stmt->error;
    } else {
        if ($stmt->affected_rows > 0) {
            echo "Entreprise supprimée avec succès.";
        } else {
            echo "Aucune entreprise trouvée avec cet ID.";
        }
    }
    $stmt->close();
    $conn->close();
} else {
    echo "ID de l'entreprise non reçu.";
}
?>
