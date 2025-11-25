<?php
session_start();

// Si l'utilisateur n'est PAS connecté (pas d'id dans la session)
if (!isset($_SESSION['user_id'])) {
    // On le redirige vers le login
    header('Location: ../login.php'); 
    exit();
}



require_once '../dao/ProduitDAO.php';
require_once '../model/Produit.php'; 

$produitDAO = new ProduitDAO(); 

// Vérifie si on a reçu l'id en GET
if (!isset($_GET['id'])) {
    echo '<script> alert("Aucun id reçu !") </script>';
    header('Location: ../index.php');

}

$id = (int)$_GET['id']; // mettre l'id dans $id en int

// Récupère tous les produits
$lesProduits = $produitDAO->afficherTous();
$produit = null;

// Tant qu'on est dans le tableau ET qu'on n'a pas trouvé le produti
$i = 0;
while ($i < count($lesProduits) && $produit === null) {
    if ($lesProduits[$i]->getId() === $id) {
        $produit = $lesProduits[$i]; // on a trouvé le produit
    }
    $i++;
}

// Vérifie si le produit a été trouvé
if (!$produit) {
    echo '<script> alert("Produit introuvable") </script>';
    header('Location: ../index.php');

}

// Si le formulaire est fait
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mettre à jour l'objet produit avec les nouvelles valeurs
    $produit->setNom($_POST['nom']);
    $produit->setDescription($_POST['description']);
    $produit->setPrix($_POST['prix']);
    $produit->setStock($_POST['stock']);

    // Envoyer l'objet mis à jour au DAO
    $produitDAO->update($produit);

    // Redirection vers la liste des produits
    header('Location: ListeProduits.php');
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Produit</title>
    <link rel="stylesheet" href="../style.css"> 
</head>
<body>

    <h1>Modifier le Produit</h1>

    <!-- bouton retour liste -->
    <a href="ListeProduits.php"><button>Retour à la liste</button></a>

    <!-- formulaire pré-rempli avec les infos du produit -->
    <form method="POST">
        <div>
            <label>Nom :</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($produit->getNom()) ?>" required><br>
        </div>

        <div>
            <label>Description :</label>
            <textarea name="description"><?= htmlspecialchars($produit->getDescription()) ?></textarea><br>
        </div>

        <div>
            <label>Prix :</label>
            <input type="number" step="0.01" name="prix" value="<?= htmlspecialchars($produit->getPrix()) ?>" required><br>
        </div>

        <div>
            <label>Stock :</label>
            <input type="number" name="stock" value="<?= htmlspecialchars($produit->getStock()) ?>" required><br>
        </div>

        <!-- bouton pour valider la modification -->
        <button type="submit">Modifier</button>
    </form>

</body>
</html>
