<?php
session_start();
if (!isset($_SESSION['login'])) {
    // Redirection vers la page d'identification avec un message d'erreur
    header("Location: identification.php?error=not_logged_in");
    exit();
}
?>
