<?php

// Si l'utilisateur n'est PAS connecté (pas d'id dans la session)
if (!isset($_SESSION['user_id'])) {
    // On le redirige vers le login
    header('Location: ../login.php'); 
    exit();
}

require_once '../dao/ProduitDAO.php';
require_once '../model/Produit.php'; //

// Si le formulaire est fait
if (isset($_POST['ajouter'])) {

    // créer un obj produit
    $produit = new Produit();

    // mettre les valeurs du formulaire dans l'objet produit
    $produit->setNom($_POST['nom']);
    $produit->setDescription($_POST['description']);
    $produit->setPrix($_POST['prix']);
    $produit->setStock($_POST['stock']);

    // créer le DAO pr pouvoir utiliser create
    $produitDAO = new ProduitDAO();

    // mettre le produit dans la bdd
    $produitDAO->create($produit);

    // Rediriger vers la liste après ajout 
    header('Location: ListeProduits.php');

}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un Produit</title>
    <link rel="stylesheet" href="../style.css"> </head>
<body>

    <h1>Ajouter un Produit</h1>

    <!-- bouton pour retourner a la liste -->
    <a href="ListeProduits.php"><button>Retour à la liste</button></a>

    <!-- formulaire d'ajout de produit -->
    <form method="POST" action="Ajout_Produit.php">


        <div>
            <label>Nom :</label>
            <input type="text" name="nom" placeholder="Nom du produit" required><br>
        </div>


        <div>
            <label>Description :</label>
            <textarea name="description" placeholder="Description"></textarea><br>
        </div>


        <div>
            <label>Prix :</label>
            <input type="number" step="0.01" name="prix" placeholder="Prix (ex: 99.99)" required><br>
        </div>


        <div>
            <label>Stock :</label>
            <input type="number" name="stock" placeholder="Quantité en stock" required><br>
        </div>


        <input type="submit" name="ajouter" value="Ajouter">


    </form>

</body>
</html>