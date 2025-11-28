<?php
session_start();

// Si l'utilisateur n'est PAS connecté (pas d'id dans la session)
if (!isset($_SESSION['user_id'])) {
    // On le redirige vers le login
    // ATTENTION : Si tu es dans le dossier 'vues', met '../login.php'
    // Si tu es à la racine (index.php), met 'login.php'
    header('Location: ../login.php'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bienvenue dans la gestion des commandes</h1>
    <div class="nav-buttons">
        <a href="./vues/ListeClients.php"><button>Gérer les Clients</button></a>
        <a href="./vues/ListeProduits.php"><button>Gérer les Produits</button></a>
        <a href="./vues/ListeCommande.php"><button>Gérer les Commandes</button></a>
    </div>

    <br>

    <a href="deconnexion.php"><button>Se déconnecter</button></a>


</body>
</html>