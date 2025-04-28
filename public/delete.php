<?php
require_once('connect.php');

if(isset($_GET['id']) && !empty($_GET['id'])){
    $id = strip_tags($_GET['id']);
    
    // Supprimer d'abord les missions associées
    $sql_delete_mission = "DELETE FROM `mission` WHERE `num_stage` = :id";
    $query_mission = $db->prepare($sql_delete_mission);
    $query_mission->bindValue(':id', $id, PDO::PARAM_INT);
    $query_mission->execute();

    // Ensuite, supprimer le stage
    $sql_delete_stage = "DELETE FROM `stage` WHERE `num_stage` = :id";
    $query_stage = $db->prepare($sql_delete_stage);
    $query_stage->bindValue(':id', $id, PDO::PARAM_INT);
    $query_stage->execute();

    // Rediriger vers la page d'index après suppression
    header('Location: liste_etudiants.php'); 
}
require_once('close.php');