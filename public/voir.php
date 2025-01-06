<?php
// Initialisation de la session et connexion à la base de données
session_start();
$host = 'localhost'; 
$dbname = 'bdd_geststages'; 
$username = 'root'; 
$password = ''; 
$role = $_SESSION['role'] ?? null; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si le paramètre num_entreprise est passé
if (!isset($_GET['num_entreprise'])) {
    die("Aucune entreprise sélectionnée !");
}

$numEntreprise = $_GET['num_entreprise'];

// Récupérer les informations de l'entreprise
try {
    $query = $pdo->prepare("
        SELECT 
            e.num_entreprise AS ID, 
            e.raison_sociale AS 'Nom entreprise', 
            CONCAT(e.rue_entreprise, ', ', e.cp_entreprise, ' ', e.ville_entreprise) AS Adresse, 
            e.nom_resp AS 'Nom du Responsable',
            e.nom_contact AS 'Nom du Contact', 
            s.libelle AS 'Spécialité', 
            e.tel_entreprise AS Téléphone, 
            e.fax_entreprise AS Fax, 
            e.site_entreprise AS Site
        FROM 
            entreprise e
        JOIN 
            spec_entreprise se ON e.num_entreprise = se.num_entreprise
        JOIN 
            specialite s ON se.num_spec = s.num_spec
        WHERE 
            e.num_entreprise = :num_entreprise
    ");
    $query->execute(['num_entreprise' => $numEntreprise]);
    $entreprise = $query->fetch();

    if (!$entreprise) {
        die("Entreprise introuvable !");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir Entreprise - <?= htmlspecialchars($entreprise['Nom entreprise']) ?></title>
    <style>
       body {
            margin: 10px;
            font-family: 'Courier New', Courier, monospace;
            background-color: #f0f0f0;
            
        }
        h1{
            text-align: center;
            color: darkblue;
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
            width: 1000px;
            border: 1px solid #ccc;
            padding: 20px;
            margin-left: 200px;
        }
        
        .info {
            margin-bottom: 20px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
        }

        .info strong {
            color: darkblue;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .buttons a {
            text-decoration: none;
            padding: 10px 15px;
            background: darkblue;
            color: #fff;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .buttons a:hover {
            background: darkblue;
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
        <div class="container">
            <!-- Affichage du nom de l'entreprise en en-tête -->
            <h1><?= htmlspecialchars($entreprise['Nom entreprise']) ?></h1>

            <?php foreach ($entreprise as $key => $value): ?>
                <div class="info">
                    <strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars($value) ?>
                </div>
            <?php endforeach; ?>

            <div class="buttons">
                <a href="modifier.php?num_entreprise=<?= $entreprise['ID'] ?>">Modifier</a>
                <a href="#" onclick="confirmDelete(<?= $entreprise['ID'] ?>)">Supprimer</a>
                <a href="entreprise.php">Retour</a>
            </div>
        </div>

    <script>
        function confirmDelete(id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cette entreprise ?")) {
                window.location.href = "supprimer.php?num_entreprise=" + id;
            }
        }
    </script>
</body>
</html>
