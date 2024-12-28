<?php
session_start();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
            color: white;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .sidebar a.active {
            background-color: #007bff;
            color: white;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .content {
            margin-left: 200px;
            padding: 20px;
        }
        .header {
            font-size: 36px;
            color: #007bff;
            text-align: center;
            margin-top: 20px;
        }
        .subheader {
            font-size: 16px;
            color: #007bff;
            text-align: center;
            margin-top: 10px;
        }
        .line {
            border-top: 1px solid #007bff;
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
            background-color: #007bff;
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
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="#" class="active"><i class="fas fa-home"></i> Accueil</a>
        <a href="#"><i class="fas fa-building"></i> Entreprise</a>
        <a href="#"><i class="fas fa-user"></i> Stagiaire</a>
        <a href="#"><i class="fas fa-handshake"></i> Inscription</a>
        <a href="#"><i class="fas fa-question-circle"></i> Aide</a>
        
        <div class="mt-auto">
            <a href="#"><i class="fas fa-arrow-right"></i> Développer</a>
            <a href="#"><i class="fas fa-arrow-left"></i> Réduire</a>
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
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelectorAll('.sidebar a').forEach(el => el.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>