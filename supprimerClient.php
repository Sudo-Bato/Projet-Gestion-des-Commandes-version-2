<?php
include './dao/ClientDAO.php';

// Vérifie si on a un id
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // mettre le id dans une variable en int pour etre sur

    $clientDAO = new ClientDAO();
    $clientDAO->delete($id);

    // Redirige vers la page après suppression
    header('Location: vues/ListeClients.php');

} else {
    echo "Aucun ID pour la suppression.";
}
?>
