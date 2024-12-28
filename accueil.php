<?php 
session_start();
if (!isset($_SESSION['login'])) {
   
    header("Location: deconnection.php");
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
            color: white;
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
            background-color: #007bff;
            color: white;
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
        <a href="#" class="active">
            <img src="icons/home.png" alt="Accueil" class="icon"> Accueil
        </a>
        <a href="#">
            <img src="icons/entreprise.png" alt="Entreprise" class="icon"> Entreprise
        </a>
        <a href="#">
            <img src="icons/stage.png" alt="Stagiaire" class="icon"> Stagiaire
        </a>
        <a href="#">
            <img src="icons/inscrire.png" alt="Inscription" class="icon"> Inscription
        </a>
        <a href="#">
            <img src="icons/aide.png" alt="Aide" class="icon"> Aide
        </a>
        <a href="#">
            <img src="icons/deconnexion.png" alt="Deconnection" class="icon"> Deconnection
        </a>
        
        <div class="mt-auto">
            <a href="#">
                <img src="icons/droite.png" alt="Développer" class="icon"> Développer
            </a>
            <a href="#">
                <img src="icons/gauche.png" alt="Réduire" class="icon"> Réduire
            </a>
        </div>
    </div>
    <div class="content">
        <div class="header">Stage BTS</div>
        <div class="subheader">Bienvenue sur la page de gestion des stages </div>
        <div class="line"></di>
    </div>

</body>
</html>
