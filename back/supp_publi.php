<?php
session_start();
include("../includes/bd.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion.php');
    exit;
}

if (isset($_GET['publi_id'])) {
    $publi_id = $_GET['publi_id'];
    $user_id = $_SESSION['user_id'];

    $res = $bdd->prepare("SELECT * FROM PUBLICATIONS WHERE publi_id = ? AND user_id = ?");
    $res->execute([$publi_id, $user_id]);
    if ($res->rowCount() > 0) {
        $res = $bdd->prepare("DELETE FROM PUBLICATIONS WHERE publi_id = ?");
        if ($res->execute([$publi_id])) {
            header('Location: ../profil.php?');
        } else {
            header('Location: ../profil.php');
        }
    } else {
        header('Location: ../profil.php');
    }
} else {
    header('Location: ../profil.php');
}
?>