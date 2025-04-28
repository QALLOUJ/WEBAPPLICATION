<?php 
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: identification.php?error=not_logged_in");
    exit();
}
$login = $_SESSION['login'];
$role = $_SESSION['role'];
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stage BTS</title>
    

    <style>
        body {
            margin: 0;
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
        .header {
            font-size: 36px;
            color:darkblue;
            text-align: center;
            margin-top: 20px;
        }
        .subheader {
            font-size: 16px;
            color: darkblue;
            text-align: center;
            margin-top: 10px;
        }
        .line {
            border-top: 1px solid darkblue;
            margin-top: 20px;
        }
        
        
        
        .mt-auto {
            margin-top: auto;
        }
        
        
    </style>
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

        
    
    <div class="content">
        <div class="header">Stage BTS</div>
        <div class="subheader">Bienvenue sur la page de gestion des stages </div>
        <div class="line"></di>
    </div>

</body>
</html>
