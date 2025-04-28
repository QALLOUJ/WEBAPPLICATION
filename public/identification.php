<?php
session_start();
if (isset($_GET['error']) && $_GET['error'] == 'not_logged_in') {
    echo "<p style='color: red; text-align: center;'>Veuillez vous connecter pour accéder à cette fonctionnalité.</p>";
}
$host = 'localhost'; 
$dbname = 'bdd_geststages'; 
$username = 'root'; 
$password = ''; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $login = $_POST['login']; 
        $password = $_POST['password']; 
        $role = $_POST['role']; 
        if ($role == 'etudiant') {
            $table = 'etudiant';
        } elseif ($role == 'professeur') {
            $table = 'professeur';
        }
        $sql = "SELECT * FROM $table WHERE login = :login AND mdp = :mdp";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['login' => $login, 'mdp' => $password]);
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();
            $_SESSION['login'] = $user['login'];
            $_SESSION['role'] = $role; 
            header("Location: accueil.php"); 
            exit();
        } else {
            echo "Identifiants incorrects. Veuillez réessayer.";
        }
    }
} catch (PDOException $e) {
    die("Erreur : la connexion à la base de données a échoué. " . $e->getMessage());
}
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des stages</title>
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
            transition: width 0.3s;
        }
        .sidebar a {
            padding: 15px 8px 15px 16px;
            text-decoration: none;
            font-size: 18px;
            color: #f0f0f0;
            display: flex;
            align-items: center;
            transition: display 0.3s;
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
            color:darkblue;
            text-align: center;
            margin-top: 10px;
        }
        .line {
            border-top: 1px solid darkblue;
            margin-top: 20px;
        }
        .login-container {
            background-color: #111;
            color: white;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            margin: 50px auto;
        }
        .login-container label {
            display: block;
            margin-bottom: 8px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: none;
        }
        .login-container input[type="radio"] {
            margin-right: 10px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: darkblue;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .mt-auto {
            margin-top: auto;
        }
        a.active {
            color: blue;
            font-weight: bold; 
        }

    </style>
</head>
<body>
    <div class="sidebar">
        <a href="identification.php"  class="<?php echo ($_SERVER['PHP_SELF'] == '/accueil.php') ? 'active' : ''; ?>">
            <img src="icons/home.png" alt="Accueil" class="icon"> Accueil
        </a>
        <a href="identification.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/entreprise.php') ? 'active' : ''; ?>">
        <img src="icons/entreprise.png" alt="Entreprise" class="icon"> Entreprise
        </a>
        <a href="identification.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/stagiaire.php') ? 'active' : ''; ?>">
        <img src="icons/stage.png" alt="Stagiaire" class="icon">Stagiaire
        </a>
        <a href="identification.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/inscription.php') ? 'active' : ''; ?>">
        <img src="icons/inscrire.png" alt="Inscription" class="icon"> Inscription
        </a>
        <a href="identification.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/aide.php') ? 'active' : ''; ?>">
        <img src="icons/aide.png" alt="Aide" class="icon"> Aide
        </a>
        <br></br>
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
    <div class="content">
        <div class="header">Gestion des stages</div>
        <div class="subheader">Vous n'êtes pas connecté. Identifiez-vous pour poursuivre la navigation.</div>
        <div class="line"></div>
        <div class="login-container">
            <form method="POST">
                <label for="login">Login :</label>
                <input type="text" id="login" name="login" required>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                <label>Vous êtes :</label>
                <input type="radio" id="etudiant" name="role" value="etudiant" required>
                <label for="etudiant">Etudiant</label>
                <input type="radio" id="professeur" name="role" value="professeur" required>
                <label for="professeur">Professeur</label>
                <button type="submit">Connexion</button>
            </form>
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


</body>
</html>
