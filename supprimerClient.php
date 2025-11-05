<?php
include './dao/ClientDAO.php';

// Vérifie si un ID a été passé en GET
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // sécurité : s'assurer que c'est un entier

    $clientDAO = new ClientDAO();
    $clientDAO->delete($id);

    // Redirige vers la page de listing après suppression
    header('Location: vues/ListeClients.php');
    exit;
} else {
    echo "Aucun ID spécifié pour la suppression.";
}
?>
