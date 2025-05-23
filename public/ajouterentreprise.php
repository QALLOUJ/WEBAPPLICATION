<?php
session_start();
// Configuration de la base de données
$host = 'localhost'; 
$dbname = 'bdd_geststages'; 
$username = 'root'; 
$password = ''; 
try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    // Vérification si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données du formulaire
        $raison_sociale = $_POST['raison_sociale'] ?? null;
        $nom_contact = $_POST['nom_contact'] ?? null;
        $nom_resp = $_POST['nom_resp'] ?? null;
        $rue_entreprise = $_POST['rue_entreprise'] ?? null;
        $cp_entreprise = $_POST['cp_entreprise'] ?? null;
        $ville_entreprise = $_POST['ville_entreprise'] ?? null;
        $tel_entreprise = $_POST['tel_entreprise'] ?? null;
        $fax_entreprise = $_POST['fax_entreprise'] ?? null;
        $email = $_POST['email'] ?? null;
        $observation = $_POST['observation'] ?? null;
        $site_entreprise = $_POST['site_entreprise'] ?? null;
        $niveau = $_POST['niveau'] ?? null;
        $specialites = $_POST['specialites'] ?? []; // Liste des spécialités sélectionnées ou saisies

        // Vérification des champs obligatoires
        if (empty($raison_sociale) || empty($nom_contact) || empty($rue_entreprise) || empty($cp_entreprise) || empty($ville_entreprise) || empty($tel_entreprise)) {
            throw new Exception("Veuillez remplir tous les champs obligatoires.");
        }

        // Démarrage de la transaction
        $pdo->beginTransaction();

        // Insertion dans la table entreprise
        $sql_entreprise = "INSERT INTO entreprise (
            raison_sociale, nom_contact, nom_resp, rue_entreprise, cp_entreprise, ville_entreprise, tel_entreprise, fax_entreprise, email, observation, site_entreprise, niveau
        ) VALUES (
            :raison_sociale, :nom_contact, :nom_resp, :rue_entreprise, :cp_entreprise, :ville_entreprise, :tel_entreprise, :fax_entreprise, :email, :observation, :site_entreprise, :niveau
        )";
        $stmt = $pdo->prepare($sql_entreprise);
        $stmt->execute([
            ':raison_sociale' => $raison_sociale,
            ':nom_contact' => $nom_contact,
            ':nom_resp' => $nom_resp,
            ':rue_entreprise' => $rue_entreprise,
            ':cp_entreprise' => $cp_entreprise,
            ':ville_entreprise' => $ville_entreprise,
            ':tel_entreprise' => $tel_entreprise,
            ':fax_entreprise' => $fax_entreprise,
            ':email' => $email,
            ':observation' => $observation,
            ':site_entreprise' => $site_entreprise,
            ':niveau' => $niveau,
        ]);

        // Récupérer le dernier ID inséré (num_entreprise)
        $num_entreprise = $pdo->lastInsertId();

        // Gestion des spécialités
        foreach ($specialites as $specialite) {
            // Vérifier si la spécialité existe déjà
            $sql_check_spec = "SELECT num_spec FROM specialite WHERE libelle = :libelle";
            $stmt = $pdo->prepare($sql_check_spec);
            $stmt->execute([':libelle' => $specialite]);
            $spec = $stmt->fetch();

            if ($spec) {
                // La spécialité existe déjà
                $num_spec = $spec['num_spec'];
            } else {
                // Insérer la nouvelle spécialité
                $sql_insert_spec = "INSERT INTO specialite (libelle) VALUES (:libelle)";
                $stmt = $pdo->prepare($sql_insert_spec);
                $stmt->execute([':libelle' => $specialite]);
                $num_spec = $pdo->lastInsertId();
            }

            // Insérer dans la table spec_entreprise
            $sql_spec_entreprise = "INSERT INTO spec_entreprise (num_entreprise, num_spec) VALUES (:num_entreprise, :num_spec)";
            $stmt = $pdo->prepare($sql_spec_entreprise);
            $stmt->execute([
                ':num_entreprise' => $num_entreprise,
                ':num_spec' => $num_spec,
            ]);
        }

        // Valider la transaction
        $pdo->commit();

        echo "Entreprise et spécialités ajoutées avec succès !";
        header("Location: entreprise.php");
        exit;
    }
} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    die("Erreur de connexion à la base de données : " . $e->getMessage());
} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    die("Erreur : " . $e->getMessage());
}


?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une entreprise</title>
    <style>
        body {
            margin: 10px;
            font-family: 'Courier New', Courier, monospace;
            background-color: #f0f0f0;
            
        }
        h1{
            text-align: center;
            margin-left: 240px;
            color: #333;
        }
        .sidebar {
            height: 100vh;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #111;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 15px 8px 15px 16px;
            text-decoration: none;
            font-size: 18px;
            color: #f0f0f0;
            display: flex;
            align-items: center;
        }
        .sidebar a img.icon {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }
        .sidebar a:hover {
            background-color: darkblue;
        }
        
        .sidebar a.active {
            background-color: darkblue; 
            color: white; 
            font-weight: bold;
        }

        .container {
            width: 800px;
            margin: 20px auto;
            
            border: 1px solid #ccc;
            padding: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-header {
            background-color: darkblue;
            color: #fff;
            padding: 10px;
            font-weight: bold;
        }
        .section-content {
            background-color: #e0e0e0;
            padding: 10px;
        }
        form {
            width: 100%; /* occupe toute la largeur de la page */
            width: 950px; /* optionnel : limite la largeur maximale */
            margin: 0 auto; /* centre le formulaire horizontalement */
            padding: 20px; /* espace interne */
             /* couleur de fond douce */
            border: 1px solid #ccc; /* bordure légère */
            border-radius: 10px; /* coins arrondis */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* ombrage */
            margin-right: 50px;
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
            background-color:darkblue;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .submit-button button:hover {
            background-color:darkblue;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="accueil.php"  class="<?php echo (basename($_SERVER['PHP_SELF']) == 'accueil.php') ? '' : ''; ?>">
        <img src="icons/home.png" alt="Accueil" class="icon"> Accueil
        </a>
        <a href="entreprise.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'entreprise.php') ? 'active' : ''; ?>">
        <img src="icons/entreprise.png" alt="Entreprise" class="icon"> Entreprise
        </a>
        
        <a href="stagiaire.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'stagiaire.php') ? '' : ''; ?>">
        <img src="icons/stage.png" alt="Stagiaire" class="icon">Stagiaire
        </a>
        <a href="inscription.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'inscription.php') ? '' : ''; ?>">
        <img src="icons/inscrire.png" alt="Inscription" class="icon"> Inscription
        </a>
        <a href="aide.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'aide.php') ? '' : ''; ?>">
        <img src="icons/aide.png" alt="Aide" class="icon"> Aide
        </a>
        <a href="identification.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'identification.php') ? '' : ''; ?>">
        <img src="icons/deconnexion.png" alt="Deconnexion" class="icon"> Deconnexion
        </a>
        <br></br>
        <br></br>
        <br></br>
        
        <div class="mt-auto">
            <a href="#" id="expandSidebar">
                <img src="icons/droite.png" alt="Développer" class="icon"> Développer
            </a>
            <a href="#" id="collapseSidebar">
                <img src="icons/gauche.png" alt="Réduire" class="icon"> Réduire
            </a>
        </div>
    </div>
        <script>
            const sidebar = document.querySelector('.sidebar');
            const expandButton = document.getElementById('expandSidebar');
            const collapseButton = document.getElementById('collapseSidebar');
            const links = document.querySelectorAll('.sidebar a');

            // Fonction pour développer la barre
            expandButton.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le rechargement de la page
                sidebar.style.width = '200px'; // Largeur normale
                links.forEach(link => {
                    link.style.display = 'flex'; // Affiche tous les liens
                });
            });

            // Fonction pour réduire la barre
            collapseButton.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le rechargement de la page
                sidebar.style.width = '50px'; // Réduit la barre
                links.forEach(link => {
                    if (!link.querySelector('img[alt="Développer"]') && !link.querySelector('img[alt="Réduire"]')) {
                        link.style.display = 'none'; // Cache les autres liens
                    }
                });
            });
        </script>
    
    <div>
        <h1>Ajouter une entreprise</h1>
        <form action="ajouterentreprise.php" method="POST">
            <div class="section">
                <div class="section-header">Information</div>
                <div class="section-content">
                    <div class="form-group">
                        <label for="raison_sociale">Nom entreprise* :</label>
                        <input type="text" id="raison_sociale" name="raison_sociale" required>
                    </div>
                    <div class="form-group">
                        <label for="nom_contact">Nom du contact* :</label>
                        <input type="text" id="nom_contact" name="nom_contact" required>
                    </div>
                    <div class="form-group">
                        <label for="nom_resp">Nom du responsable :</label>
                        <input type="text" id="nom_resp" name="nom_resp">
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="section-header">Contact</div>
                <div class="section-content">
                    <div class="form-group">
                        <label for="rue_entreprise">Rue* :</label>
                        <input type="text" id="rue_entreprise" name="rue_entreprise" required>
                    </div>
                    <div class="form-group">
                        <label for="cp_entreprise">Code postal* :</label>
                        <input type="text" id="cp_entreprise" name="cp_entreprise" required>
                    </div>
                    <div class="form-group">
                        <label for="ville_entreprise">Ville* :</label>
                        <input type="text" id="ville_entreprise" name="ville_entreprise" required>
                    </div>
                    <div class="form-group">
                        <label for="tel_entreprise">Téléphone* :</label>
                        <input type="tel" id="tel_entreprise" name="tel_entreprise" required>
                    </div>
                    <div class="form-group">
                        <label for="fax_entreprise">Fax :</label>
                        <input type="tel" id="fax_entreprise" name="fax_entreprise">
                    </div>
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email">
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="section-header">Divers</div>
                <div class="section-content">
                    <div class="form-group">
                        <label for="observation">Observation :</label>
                        <textarea id="observation" name="observation"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="site_entreprise">Url de site :</label>
                        <input type="url" id="site_entreprise" name="site_entreprise">
                    </div>
                    <div class="form-group">
                        <label for="niveau">Niveau* :</label>
                        <input type="text" id="niveau" name="niveau" required>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="section-header">Spécialité</div>
                <div class="section-content">
                    <div class="form-group">
                        <label for="speciality">Spécialité :</label>
                        <select id="speciality" name="speciality[]" multiple>
                            <option>SLAM</option>
                            <option>SISR</option>
                            <option>AM</option>
                            <option>SN</option>
                            <option>CG</option>
                            <option>NRC</option>
                        </select>
                    </div>
                </div>
            </div>
            
            
            
            <div class="note">
                Les champs comportant le symbole * sont obligatoires
            </div>
            <div class="submit-button">
                <button type="submit">Ajouter</button>
            </div>
        </form>
    </div>
</body>
</html>