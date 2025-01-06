<?php
// vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // initialiser tous les champs obligatoires
    $raison_sociale = $_POST['raison_sociale'] ?? '';
    $nom_contact = $_POST['nom_contact'] ?? '';
    $rue_entreprise = $_POST['rue_entreprise'] ?? '';
    $cp_entreprise = $_POST['cp_entreprise'] ?? '';
    $ville_entreprise = $_POST['ville_entreprise'] ?? '';
    $tel_entreprise = $_POST['tel_entreprise'] ?? '';
    $email = $_POST['email'] ?? '';
    $observation = $_POST['observation'] ?? '';

    // liste des champs obligatoires
    $required_fields = ['raison_sociale', 'nom_contact', 'rue_entreprise', 'cp_entreprise', 'ville_entreprise', 'tel_entreprise'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die("veuillez remplir tous les champs obligatoires.");
        }
    }

    // connexion à la base de données
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=nom_de_la_base', 'utilisateur', 'mot_de_passe');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // mise à jour des informations dans la base de données
        $query = "UPDATE entreprises SET 
            raison_sociale = :raison_sociale,
            nom_contact = :nom_contact,
            rue = :rue_entreprise,
            cp = :cp_entreprise,
            ville = :ville_entreprise,
            tel = :tel_entreprise,
            email = :email,
            observation = :observation
            WHERE id = :id";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':raison_sociale' => $raison_sociale,
            ':nom_contact' => $nom_contact,
            ':rue_entreprise' => $rue_entreprise,
            ':cp_entreprise' => $cp_entreprise,
            ':ville_entreprise' => $ville_entreprise,
            ':tel_entreprise' => $tel_entreprise,
            ':email' => $email,
            ':observation' => $observation,
            ':id' => $_POST['id'], // id de l'entreprise à modifier
        ]);

        // redirection vers la page d'accueil après modification
        header("Location: index.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("erreur lors de la mise à jour : " . $e->getMessage());
    }
} else {
    // récupération des données actuelles pour pré-remplir le formulaire
    if (!isset($_GET['id'])) {
        die("id non spécifié.");
    }

    $id = $_GET['id'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=nom_de_la_base', 'utilisateur', 'mot_de_passe');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM entreprises WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $id]);
        $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$entreprise) {
            die("entreprise introuvable.");
        }
    } catch (PDOException $e) {
        die("erreur lors de la récupération des données : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>modifier entreprise</title>
    <!-- ajouter votre fichier css -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-header {
            background-color: #3366cc;
            color: #fff;
            padding: 10px;
            font-weight: bold;
        }
        .section-content {
            background-color: #e0e0e0;
            padding: 10px;
        }
        .form-group {
            display: flex;
            margin-bottom: 10px;
        }
        .form-group label {
            width: 200px;
            font-weight: bold;
        }
        .form-group input, .form-group textarea, .form-group select {
            flex: 1;
            padding: 5px;
            border: 1px solid #ccc;
        }
        .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="tel"], .form-group input[type="url"] {
            width: 100%;
        }
        .form-group textarea {
            width: 100%;
            height: 60px;
        }
        .form-group select {
            width: 100%;
            height: 100px;
        }
        .note {
            background-color: #ccffcc;
            padding: 10px;
            text-align: center;
        }
        .submit-button {
            text-align: center;
        }
        .submit-button button {
            background-color: #3366cc;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .submit-button button:hover {
            background-color: #003399;
        }
    </style>

</head>
<body>
    <h1>modifier les informations de l'entreprise</h1>
    <form method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($entreprise['id']) ?>">
        <label for="raison_sociale">raison sociale :</label>
        <input type="text" name="raison_sociale" id="raison_sociale" value="<?= htmlspecialchars($entreprise['raison_sociale']) ?>" required>

        <label for="nom_contact">nom du contact :</label>
        <input type="text" name="nom_contact" id="nom_contact" value="<?= htmlspecialchars($entreprise['nom_contact']) ?>" required>

        <label for="rue_entreprise">rue :</label>
        <input type="text" name="rue_entreprise" id="rue_entreprise" value="<?= htmlspecialchars($entreprise['rue']) ?>" required>

        <label for="cp_entreprise">code postal :</label>
        <input type="text" name="cp_entreprise" id="cp_entreprise" value="<?= htmlspecialchars($entreprise['cp']) ?>" required>

        <label for="ville_entreprise">ville :</label>
        <input type="text" name="ville_entreprise" id="ville_entreprise" value="<?= htmlspecialchars($entreprise['ville']) ?>" required>

        <label for="tel_entreprise">téléphone :</label>
        <input type="text" name="tel_entreprise" id="tel_entreprise" value="<?= htmlspecialchars($entreprise['tel']) ?>" required>

        <label for="email">email :</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($entreprise['email']) ?>">

        <label for="observation">observations :</label>
        <textarea name="observation" id="observation"><?= htmlspecialchars($entreprise['observation']) ?></textarea>

        <button type="submit">enregistrer</button>
    </form>
</body>
</html>
