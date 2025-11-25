<?php
// Import des DAO nécessaires pour gérer clients, produits et commandes
require_once '../dao/ClientDAO.php';
require_once '../dao/ProduitDAO.php';
require_once '../dao/CommandeDAO.php';

// --- 1. Chargement des données nécessaires pour le formulaire ---

// Récupération de tous les clients pour remplir le menu déroulant
$clientDAO = new ClientDAO();
$lesClients = $clientDAO->afficherTous();

// Récupération de tous les produits pour proposer la sélection multiple
$produitDAO = new ProduitDAO();
$lesProduits = $produitDAO->afficherTous();


// --- 2. Traitement du formulaire lorsque l'utilisateur valide la commande ---
if (isset($_POST['valider_commande'])) {
    
    // ID du client choisi
    $client_id = $_POST['client'];
    
    // Récupération des tableaux envoyés par le formulaire
    $produits_ids = $_POST['produit']; // Tableau des produits choisis
    $quantites = $_POST['quantite'];   // Tableau des quantités associées

    // Construction d’un tableau propre pour le DAO (le "panier")
    $panier = [];
    for ($i = 0; $i < count($produits_ids); $i++) {

        // On ajoute une ligne au panier uniquement si :
        // - un produit est choisi
        // - la quantité est supérieure à 0
        if (!empty($produits_ids[$i]) && $quantites[$i] > 0) {
            $panier[] = [
                'id_produit' => $produits_ids[$i],
                'qte' => $quantites[$i]
            ];
        }
    }

    // Si au moins un produit valide a été ajouté
    if (!empty($panier)) {
        $commandeDAO = new CommandeDAO();
        
        // Création de la commande (avec MAJ automatique du stock en interne)
        $commandeDAO->create($client_id, $panier);
        
        // Redirection vers la liste des commandes
        header('Location: ListeCommande.php');
        exit;
    } else {
        // Message d’erreur si rien n’a été sélectionné
        echo "<script>alert('Veuillez sélectionner au moins un produit.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle Commande</title>

    <!-- Feuille de style générale -->
    <link rel="stylesheet" href="../style.css">

    <!-- Styles spécifiques à cette page -->
    <style>
        .ligne-produit { 
            margin-bottom: 10px; 
            border-bottom: 1px solid #ddd; 
            padding-bottom: 10px; 
        }
        .btn-add { 
            background-color: #28a745; 
            color: white; 
            border: none; 
            padding: 5px 10px; 
            cursor: pointer; 
        }
        .btn-remove { 
            background-color: #dc3545; 
            color: white; 
            border: none; 
            padding: 5px 10px; 
            cursor: pointer; 
            margin-left: 10px;
        }
    </style>
</head>

<body>

    <h1>Ajouter une Commande</h1>

    <!-- Bouton retour vers la liste -->
    <a href="ListeCommande.php"><button>Retour</button></a>

    <!-- Formulaire d'ajout de commande -->
    <form method="POST" style="margin-top: 20px;">
        
        <!-- Sélection du client -->
        <label><strong>Client :</strong></label><br>
        <select name="client" required>
            <option value="">-- Choisir un client --</option>

            <!-- Liste des clients -->
            <?php foreach ($lesClients as $client): ?>
                <option value="<?= $client->getId() ?>">
                    <?= htmlspecialchars($client->getNom()) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <!-- Sélection des produits -->
        <label><strong>Produits commandés :</strong></label>

        <!-- Conteneur regroupant toutes les lignes produits -->
        <div id="container-produits">

            <!-- Première ligne produit (modèle pour les duplications JS) -->
            <div class="ligne-produit">
                
                <!-- Liste déroulante des produits -->
                <select name="produit[]" required>
                    <option value="">-- Choisir un produit --</option>

                    <?php foreach ($lesProduits as $produit): ?>
                        <option value="<?= $produit->getId() ?>">
                            <?= htmlspecialchars($produit->getNom()) ?>
                            (<?= $produit->getPrix() ?> €)
                            - Stock: <?= $produit->getStock() ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Champ quantité -->
                <input type="number" name="quantite[]" placeholder="Quantité" min="1" required style="width: 80px;">
            </div>
        </div>
        

        <!-- Bouton valider -->
        <input type="submit" name="valider_commande" value="Valider la commande">

    </form>

</body>
</html>
