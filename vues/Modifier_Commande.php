<?php
session_start();

// Si l'utilisateur n'est PAS connecté (pas d'id dans la session)
if (!isset($_SESSION['user_id'])) {
    // On le redirige vers le login
    header('Location: ../login.php'); 
    exit();
}


// Chargement des DAO nécessaires
require_once '../dao/CommandeDAO.php';
require_once '../dao/ClientDAO.php';

// --- Vérification de la présence de l'ID de commande ---
// Si aucun ID n'est fourni, on redirige vers la liste
if (!isset($_GET['id'])) {
    header('Location: ListeCommande.php');
    exit;
}

$id = (int)$_GET['id']; // Sécurisation minimale en castant en entier

$commandeDAO = new CommandeDAO();
// Récupération de la commande associée à l'ID
$commande = $commandeDAO->getById($id);

// --- Traitement du formulaire de modification ---
// Si le formulaire est soumis (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Nouveau statut choisi par l'utilisateur
    $nouveauStatut = $_POST['statut'];

    // Mise à jour du statut dans la base via le DAO
    $commandeDAO->updateStatut($id, $nouveauStatut);

    // Retour à la liste des commandes une fois terminé
    header('Location: ListeCommande.php');
    exit;
}

// --- Récupération des données pour affichage uniquement ---
// (détails des produits + client)
$details = $commandeDAO->getDetailsCommande($id);

$clientDAO = new ClientDAO();
$client = $clientDAO->getById($commande->getClientId());
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Commande</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <!-- Titre principal -->
    <h1>Modifier le statut de la commande #<?= $commande->getId() ?></h1>

    <!-- Bouton retour -->
    <a href="ListeCommande.php"><button>Retour</button></a>

    <!-- Bloc affichant les infos de la commande -->
    <div style="margin-top: 20px; border: 1px solid #ccc; padding: 20px;">

        <!-- Informations client -->
        <p>
            <strong>Client :</strong> 
            <?= $client ? htmlspecialchars($client->getNom()) : 'Inconnu' ?>
        </p>

        <!-- Date de la commande -->
        <p><strong>Date :</strong> <?= $commande->getDateCommande() ?></p>
        
        <!-- Liste des produits de la commande -->
        <p><strong>Produits :</strong></p>
        <ul>
            <?php foreach ($details as $d): ?>
                <li>
                    <?= htmlspecialchars($d['nom']) ?> 
                    (x<?= $d['quantite'] ?>)
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Formulaire de changement de statut -->
        <form method="POST">
            <label><strong>Statut actuel :</strong></label>

            <select name="statut">
                <!-- Sélection du statut en fonction de celui actuel -->
                <option value="en cours"   <?= $commande->getStatut() == 'en cours'   ? 'selected' : '' ?>>En cours</option>
                <option value="expédiée"   <?= $commande->getStatut() == 'expédiée'   ? 'selected' : '' ?>>Expédiée</option>
                <option value="livrée"     <?= $commande->getStatut() == 'livrée'     ? 'selected' : '' ?>>Livrée</option>
                <option value="annulée"    <?= $commande->getStatut() == 'annulée'    ? 'selected' : '' ?>>Annulée</option>
            </select>

            <br><br>

            <!-- Bouton de validation -->
            <button type="submit">Mettre à jour le statut</button>
        </form>
    </div>

</body>
</html>
