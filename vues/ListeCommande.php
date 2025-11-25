<?php
session_start();

// Si l'utilisateur n'est PAS connecté (pas d'id dans la session)
if (!isset($_SESSION['user_id'])) {
    // On le redirige vers le login
    header('Location: ../login.php'); 
    exit();
}

require_once '../dao/CommandeDAO.php';
require_once '../dao/ClientDAO.php'; // On récupère aussi les infos client pour afficher le nom

$commandeDAO = new CommandeDAO();
$clientDAO = new ClientDAO();

// On récupère toutes les commandes pour les afficher dans le tableau
$lesCommandes = $commandeDAO->afficherTous();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Commandes</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <h1>Gestion des Commandes</h1>

    <div id="top-section">
        <!-- Navigation simple -->
        <a href="../index.php"><button>Retour à l'accueil</button></a>
        <a href="Ajout_Commande.php"><button>Ajouter une Commande</button></a>
    </div>

    <div id="tableau">
        <table>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Produits (Détails)</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($lesCommandes as $commande): ?>
                
                <?php 
                    // On récupère le client associé (pour afficher son nom dans le tableau)
                    $client = $clientDAO->getById($commande->getClientId());
                    $nomClient = $client ? $client->getNom() : "Client inconnu";

                    // On récupère les produits et quantités associés à la commande
                    $details = $commandeDAO->getDetailsCommande($commande->getId());
                ?>

                <tr>
                    <!-- Affichage des données principales -->
                    <td><?= $commande->getId() ?></td>
                    <td><?= htmlspecialchars($nomClient) ?></td>
                    <td><?= $commande->getDateCommande() ?></td>
                    <td><?= htmlspecialchars($commande->getStatut()) ?></td>
                    
                    <!-- Liste des produits inclus dans la commande -->
                    <td>
                        <?php foreach ($details as $d): ?>
                            <!-- On affiche : nom du produit, quantité, prix -->
                            - <?= htmlspecialchars($d['nom']) ?> x <?= $d['quantite'] ?> (<?= $d['prix'] ?>€)<br>
                        <?php endforeach; ?>
                    </td>

                    <td>
                        <!-- Lien vers la page de modification -->
                        <a href="Modifier_Commande.php?id=<?= $commande->getId() ?>"><button>Modifier</button></a>
                        
                        <!-- Suppression de la commande (stock remis automatiquement dans DAO) -->
                        <a href="../supprimerCommande.php?id=<?= $commande->getId() ?>"><button>Supprimer</button></a>
                    </td>
                </tr>

            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>
