<?php

include './dao/ProduitDAO.php';

// Vérifie y'a un id
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // mettre le id dans une variable en int pour etre sur

    $produitDAO = new ProduitDAO();
    $produitDAO->delete($id);

    // Redirige vers la page après avoir suppr
    header('Location: vues/ListeProduits.php');

} else {
    echo "Aucun ID pour la suppression.";
}
?>