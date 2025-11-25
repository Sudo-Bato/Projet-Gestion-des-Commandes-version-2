<?php
// Protection session à ajouter plus tard si besoin
require_once 'dao/CommandeDAO.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $commandeDAO = new CommandeDAO();
    $commandeDAO->delete($id);
    
    header('Location: vues/ListeCommande.php');
}
?>