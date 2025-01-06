<?php
// Configuration de la base de données
$host = 'localhost'; 
$dbname = 'bdd_geststages'; 
$username = 'root'; 
$password = ''; 

try {
    // Connexion à la base
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Récupération des critères de recherche
    $niveau = $_GET['niveau'] ?? '';
    $ville = $_GET['ville'] ?? '';
    $specialite = $_GET['specialite'] ?? '';

    // Construction de la requête
    $sql = "SELECT e.raison_sociale AS entreprise, 
                   e.nom_resp AS responsable, 
                   CONCAT(e.rue_entreprise, ', ', e.cp_entreprise, ' ', e.ville_entreprise) AS adresse, 
                   e.site_entreprise AS site, 
                   s.libelle AS specialite
            FROM entreprise e
            JOIN spec_entreprise se ON e.num_entreprise = se.num_entreprise
            JOIN specialite s ON se.num_spec = s.num_spec
            WHERE 1=1";

    $params = [];
    if (!empty($niveau)) {
        $sql .= " AND e.niveau = :niveau";
        $params[':niveau'] = $niveau;
    }
    if (!empty($ville)) {
        $sql .= " AND e.ville_entreprise = :ville";
        $params[':ville'] = $ville;
    }
    if (!empty($specialite)) {
        $sql .= " AND s.libelle = :specialite";
        $params[':specialite'] = $specialite;
    }

    // Exécution de la requête
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Récupération des résultats
    $resultats = $stmt->fetchAll();

    
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher une entreprise</title>
    <style>
        /* Style général */
        body {
            font-family: Arial, sans-serif;
            background-color:  #f0f0f0;
            margin: 0;
            padding: 20px;
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
            background-color: #575757;
        }
        .sidebar a.active {
            background-color: darkblue;
            color: #f0f0f0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }

        /* Conteneur principal */
        .container {
            max-width: 700px;
            
            padding: 110px;
            background-color: #fff;
            border: 1px solid #ddd;
            
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        
            margin: 0 auto;
            margin-top: 10px;
            
            border-radius: 10px;
        
            
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        select, button {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        select:focus, button:focus {
            outline: none;
            border-color: darkblue;
            box-shadow: 0 0 5px darkblue;
        }

        button {
            background-color: darkblue;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 0 auto;
            display: block;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Table des résultats */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse; /* Pour supprimer les espaces entre les bordures */
        }


        table th, table td {
            padding: 6px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Lien de retour */
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="sidebar">
            <a href="accueil.php"  class="<?php echo ($_SERVER['PHP_SELF'] == '/accueil.php') ? 'active' : ''; ?>">
                <img src="icons/home.png" alt="Accueil" class="icon"> Accueil
            </a>
            <a href="entreprise.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/entreprise.php') ? 'active' : ''; ?>">
            <img src="icons/entreprise.png" alt="Entreprise" class="icon"> Entreprise
            </a>
            <a href="stagiaire.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/stagiaire.php') ? 'active' : ''; ?>">
            <img src="icons/stage.png" alt="Stagiaire" class="icon">Stagiaire
            </a>
            <a href="inscription.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/inscription.php') ? 'active' : ''; ?>">
            <img src="icons/inscrire.png" alt="Inscription" class="icon"> Inscription
            </a>
            <a href="aide.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/aide.php') ? 'active' : ''; ?>">
            <img src="icons/aide.png" alt="Aide" class="icon"> Aide
            </a>
            <a href="identification.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/identification.php') ? 'active' : ''; ?>">
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
        <h1>Rechercher une entreprise</h1>
        <form method="GET" action="rechercherentreprise.php">
            <!-- Liste déroulante pour le niveau -->
            <div style="flex: 1 1 calc(33.333% - 20px);">
                <label for="niveau">Niveau :</label>
                <select name="niveau" id="niveau">
                    <option value="">--Sélectionnez un niveau--</option>
                    <?php
                    // Récupérer les niveaux uniques depuis la base
                    $query = $pdo->query("SELECT DISTINCT niveau FROM entreprise ORDER BY niveau ASC");
                    while ($row = $query->fetch()) {
                        echo "<option value='{$row['niveau']}'>{$row['niveau']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Liste déroulante pour la ville -->
            <div style="flex: 1 1 calc(33.333% - 20px);">
                <label for="ville">Ville :</label>
                <select name="ville" id="ville">
                    <option value="">--Sélectionnez une ville--</option>
                    <?php
                    // Récupérer les villes uniques depuis la base
                    $query = $pdo->query("SELECT DISTINCT ville_entreprise FROM entreprise ORDER BY ville_entreprise ASC");
                    while ($row = $query->fetch()) {
                        echo "<option value='{$row['ville_entreprise']}'>{$row['ville_entreprise']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Liste déroulante pour la spécialité -->
            <div style="flex: 1 1 calc(33.333% - 20px);">
                <label for="specialite">Spécialité :</label>
                <select name="specialite" id="specialite">
                    <option value="">--Sélectionnez une spécialité--</option>
                    <?php
                    // Récupérer les spécialités uniques depuis la base
                    $query = $pdo->query("SELECT DISTINCT libelle FROM specialite ORDER BY libelle ASC");
                    while ($row = $query->fetch()) {
                        echo "<option value='{$row['libelle']}'>{$row['libelle']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Bouton de recherche -->
            <div style="flex: 1 1 100%;">
                <button type="submit">Rechercher</button>
            </div>
        </form>

        <!-- Tableau des résultats -->
        <?php if (isset($resultats) && count($resultats) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Entreprise</th>
                    <th>Responsable</th>
                    <th>Adresse</th>
                    <th>Site</th>
                    <th>Spécialité</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultats as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['entreprise']) ?></td>
                    <td><?= htmlspecialchars($row['responsable']) ?></td>
                    <td><?= htmlspecialchars($row['adresse']) ?></td>
                    <td><?= htmlspecialchars($row['site']) ?></td>
                    <td><?= htmlspecialchars($row['specialite']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Aucun résultat trouvé pour les critères sélectionnés.</p>
        <?php endif; ?>

        <a href="entreprise.php" class="back-link">Retour à la liste des entreprises</a>
    </div>
</body>
</html>
