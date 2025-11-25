<?php
session_start();

// Si l'utilisateur n'est PAS connecté (pas d'id dans la session)
if (!isset($_SESSION['user_id'])) {
    // On le redirige vers le login
    header('Location: ../login.php'); 
    exit();
}

require_once '../dao/ProduitDAO.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <link rel="stylesheet" href="../style.css"> 
</head>
<body>

    <h1>Gestion des Produits</h1>

    <div id="top-section">
        <!-- bouton pour revenir a l'accueil -->
        <a href="../index.php"><button>Retour à l'accueil</button></a>

        <!-- bouton pour aller a la page d'ajout -->
        <a href="./Ajout_Produit.php"><button>Ajouter un Produit</button></a>
    </div>

    <div id="tableau">
        <table>
            <tr>
                <!-- en-têtes du tableau -->
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>

            <?php
                // créer le DAO pour recupérer les produits
                $produitDAO = new ProduitDAO();

                // recup tous les produits
                $lesProduits = $produitDAO->afficherTous();

                // parcourir la liste et afficher chaque produit dans le tableau
                foreach ($lesProduits as $unProduit) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($unProduit->getId()) . "</td>";
                    echo "<td>" . htmlspecialchars($unProduit->getNom()) . "</td>";
                    echo "<td>" . htmlspecialchars($unProduit->getDescription()) . "</td>";
                    echo "<td>" . htmlspecialchars($unProduit->getPrix()) . " €</td>";
                    echo "<td>" . htmlspecialchars($unProduit->getStock()) . "</td>";

                    // boutons modifier et supprimer pour chaque produit
                    // envoyer sur la page par rapport a l'id du prodiuit
                    echo "<td>
                            <a href='Modifier_Produit.php?id=" . $unProduit->getId() . "'><button>Modifier</button></a>
                            <a href='../supprimerProduit.php?id=" . $unProduit->getId() . "'><button>Supprimer</button></a>
                        </td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
</body>
</html>
