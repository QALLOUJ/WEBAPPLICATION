<?php
session_start();
if (!isset($_SESSION['login'])) {
    // Redirection vers la page d'identification avec un message d'erreur
    header("Location: identification.php?error=not_logged_in");
    exit();
}
?>

<?php


$role = $_SESSION['role'] ?? null; 

try {
    $pdo = new PDO('mysql:host=localhost;dbname=bdd_geststages', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    // récupérer les données nécessaires
    $query = $pdo->query("
        SELECT 
            e.num_entreprise AS num_entreprise,
            raison_sociale AS Entreprise, 
            CONCAT(rue_entreprise, ', ', cp_entreprise, ' ', ville_entreprise) AS Adresse, 
            nom_resp AS Responsable,
            nom_contact AS Contact, 
            libelle AS Specialite, 
            tel_entreprise AS Telephone, 
            fax_entreprise AS Fax, 
            site_entreprise AS Site
            
            
        FROM 
            entreprise e
        JOIN 
            spec_entreprise se ON e.num_entreprise = se.num_entreprise
        JOIN 
            specialite s ON se.num_spec = s.num_spec
        
    ");
    $entreprise = $query->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    
    <style>
        body {
            margin: 10px;
            font-family: 'Courier New', Courier, monospace;
            background-color: #f0f0f0;
            
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
            background-color: darkblue; /* couleur active */
            color: white; /* texte blanc */
            font-weight: bold;
        }
        .content {
            margin-left: 200px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: 200px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color:darkblue;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .actions {
            padding: 8px;
            width: 200px;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ddd
        }
        .actions select {
            padding: 8px;
            margin-left: 200px;
        }
        .actions button {
            padding: 12px 20px;
            background-color: darkblue;
            color: darkblue;
            border:0.2px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 200px;
        }
        .actions button:hover {
            background-color: #0056b3;
        }
        .delete-button {
            padding: 4px 7px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: darkred;
        }
        .line {
            border-top: 1px solid darkblue;
            margin-top: 20px;
        }
        .icon-link .image-container {
            display: inline-block;  
            background-color: darkblue; 
            padding: 5px;           
            border-radius: 5px;
            width: 30px;   
            height: 30px;  

        }
        .icon-link .image-container img {
            width: 5px;   
            height: 5px;  
            display: block;
            transition: background-color 0.3s ease; 
        }
        .icon-link .image-container:hover {
            background-color: darkblue;
            height: 5px;
            width: 5px;
        }
        
        .button-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            margin: 5px;
            background-color: darkblue; /* Couleur bleu foncé */
            color: #fff; /* Couleur du texte blanche */
            text-decoration: none;
            border-radius: 5px; /* Coins arrondis */
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Ombre */
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .button-container:hover {
            background-color: #244a9c; /* Couleur bleu clair au survol */
            transform: translateY(-2px); /* Légère élévation au survol */
        }

        .button-icon {
            width: 16px; /* Ajustez la taille de l'icône */
            height: 16px;
            margin-right: 8px; /* Espace entre l'icône et le texte */
        }




    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hiddenColumns = [];

            // masquer une colonne
            document.querySelectorAll('.btn-hide').forEach(button => {
                button.addEventListener('click', () => {
                    const column = button.dataset.column;
                    document.querySelectorAll(`.col-${column}`).forEach(cell => cell.style.display = 'none');
                    hiddenColumns.push(column);
                    updateHiddenOptions();
                });
            });

            // afficher une colonne masquée
            document.getElementById('add-column').addEventListener('click', () => {
                const select = document.getElementById('hidden-columns');
                const column = select.value;
                document.querySelectorAll(`.col-${column}`).forEach(cell => cell.style.display = '');
                hiddenColumns.splice(hiddenColumns.indexOf(column), 1);
                updateHiddenOptions();
            });

            // mettre à jour les options de colonnes masquées
            function updateHiddenOptions() {
                const select = document.getElementById('hidden-columns');
                select.innerHTML = '';
                hiddenColumns.forEach(column => {
                    const option = document.createElement('option');
                    option.value = column;
                    option.textContent = column;
                    select.appendChild(option);
                });
            }
        });
    </script>
</head>
<body>
<div class="sidebar">
        <a href="accueil.php"  class="<?php echo (basename($_SERVER['PHP_SELF']) == 'accueil.php') ? 'active' : ''; ?>">
        <img src="icons/home.png" alt="Accueil" class="icon"> Accueil
        </a>
        <a href="entreprise.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'entreprise.php') ? '' : ''; ?>">
        <img src="icons/entreprise.png" alt="Entreprise" class="icon"> Entreprise
        </a>
        
        <a href="liste_etudiants.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'liste_etudiants.php') ? '' : ''; ?>">
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
    <table border="1">
        <thead>
            <tr>
                <?php
                // afficher les en-têtes de colonnes avec des boutons pour les masquer
                if (!empty($entreprise)) {
                    echo "<th>Opérations</th>";
                    
                    foreach (array_keys($entreprise[1]) as $column) {
                        echo "<th class='col-{$column}'>{$column} <button class='btn-hide' data-column='{$column}'>-</button></th>";
                    }
                    
                } else {
                    echo "<th>Aucune donnée à afficher</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // afficher les lignes de données
            
            foreach ($entreprise as $row) {
                echo '<tr>';
                echo '<td class="col-operations">';
                
                // Vérifier si la clé 'num_entreprise' existe avant de l'utiliser
                $numEntreprise = isset($row['num_entreprise']) ? $row['num_entreprise'] : null;
            
                if ($role == 'professeur' && $numEntreprise !== null) {
                    echo "<a href='inscription.php?num_entreprise={$numEntreprise}' class='icon-link' title='Inscrire'>";
                    echo "<img src='icons/inscrire.png' alt='Inscrire'  class='image-container'>";
                    echo "</a>";
            
                    echo "<button class='icon-link' onclick='confirmDelete({$numEntreprise})' title='Supprimer'>";
                    echo "<img src='icons/supprimer.png' alt='Supprimer' class='image-container'>";
                    echo "</button>";
            
                    echo "<a href='voir.php?num_entreprise={$numEntreprise}' class='icon-link' title='Voir'>";
                    echo "<img src='icons/voir.png' alt='Voir' class='image-container'>";
                    echo "</a>";
            
                    echo "<a href='modifier.php?num_entreprise={$numEntreprise}' class='icon-link' title='Modifier'>";
                    echo "<img src='icons/modifier.png' alt='Modifier' class='image-container'>";
                    echo "</a>";
                } elseif ($role == 'etudiant') {
                    echo "<a href='voir.php?num_entreprise={$numEntreprise}' class='icon-link' title='Voir'>";
                    echo "<img src='icons/voir.png' alt='Voir' class='image-container'>";
                    echo "</a>";
                    echo "<a href='inscription.php?num_entreprise={$numEntreprise}' class='icon-link' title='Inscrire'>";
                    echo "<img src='icons/inscrire.png' alt='Inscrire' class='image-container'>";
                    echo "</a>";

                }
            
                echo '</td>';
            
                // Afficher les colonnes restantes
                foreach ($row as $column => $value) {
                    if ($column === 'Site') {
                        echo "<td class='col-{$column}'><a href='{$value}' target='_blank' class='icon-link'><img src='icons/lien.png' alt='Lien' class='image-container'></a></td>";
                    } else {
                        echo "<td class='col-{$column}'>{$value}</td>";
                    }
                }
            
                echo '</tr>';
            }
            ?>
            
            <script>
            // Fonction JavaScript pour confirmer la suppression
            function confirmDelete(numEntreprise) {
                if (confirm("Êtes-vous sûr de vouloir supprimer cette entreprise ?")) {
                    // Redirection vers une page PHP qui gère la suppression
                    window.location.href = "supprimer.php?num_entreprise=" + numEntreprise;
                }
            }
            </script>
            
            
    <div class="content">
        <a href="ajouterentreprise.php" class="button-container "><img src="icons/ajouter.png" alt="Ajouter" class="button-icon  ">Ajouter une entreprise</a>
        <a href="rechercherentreprise.php" class="button-container"><img src="icons/rechercher.png" alt="Rechercher" class="button-icon">Rechercher une entreprise</a>
        <div class="line"></div>
        <br></br>

        <select id="hidden-columns"></select>
        <button id="add-column" class="button-container">Ajouter une information</button>
    </div>
    
        
    
        </tbody>
    </table>

    
</body>
</html>
