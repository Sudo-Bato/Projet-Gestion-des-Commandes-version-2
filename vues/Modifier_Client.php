<?php
require_once '../dao/ClientDAO.php';
require_once '../model/Client.php';

$clientDAO = new ClientDAO();

// Vérifie si on a reçu l'id du client
if (!isset($_GET['id'])) {
    die("Aucun client spécifié !");
}

$id = (int)$_GET['id'];

// Récupère le client
$lesClients = $clientDAO->afficherTous();
$client = null;
foreach ($lesClients as $c) {
    if ($c->getId() === $id) {
        $client = $c;
        break;
    }
}

if (!$client) {
    die("Client introuvable !");
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client->setNom($_POST['nom']);
    $client->setEmail($_POST['email']);
    $client->setTelephone($_POST['telephone']);
    $client->setAdresseRue($_POST['adresse_rue']);
    $client->setAdresseCp($_POST['adresse_cp']);
    $client->setAdresseVille($_POST['adresse_ville']);

    $clientDAO->update($client);

    header('Location: ListeClients.php'); // retourne à la liste
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Client</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Modifier Client</h1>
    <a href="Afficher_Clients.php"><button>Retour à la liste</button></a>

    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($client->getNom()) ?>" required><br>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($client->getEmail()) ?>" required><br>

        <label>Téléphone :</label>
        <input type="text" name="telephone" value="<?= htmlspecialchars($client->getTelephone()) ?>"><br>

        <label>Adresse Rue :</label>
        <input type="text" name="adresse_rue" value="<?= htmlspecialchars($client->getAdresseRue()) ?>"><br>

        <label>Code Postal :</label>
        <input type="text" name="adresse_cp" value="<?= htmlspecialchars($client->getAdresseCp()) ?>"><br>

        <label>Ville :</label>
        <input type="text" name="adresse_ville" value="<?= htmlspecialchars($client->getAdresseVille()) ?>"><br>

        <button type="submit">Modifier</button>
    </form>
</body>
</html>
