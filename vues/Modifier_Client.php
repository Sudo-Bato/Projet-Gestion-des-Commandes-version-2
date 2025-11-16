<?php
require_once '../dao/ClientDAO.php'; 
require_once '../model/Client.php';  

$clientDAO = new ClientDAO(); 



// Vérifie si on a reçu l'id du client en paramètre
if (!isset($_GET['id'])) {
    echo '<script> alert ("j ai pas l id du client") </script>';
    header('Location: ../index.php');
}

$id = (int)$_GET['id']; // mettre l'id en int dans $id

// recup la liste des clients
$lesClients = $clientDAO->afficherTous();

// On cherche le client de l'id récupéré
$i = 0;
$client = null;

// Tant qu'on est dans le tableau ET qu'on n'a pas trouvé le client
while ($i < count($lesClients) && $client === null) {

    if ($lesClients[$i]->getId() === $id) {
        $client = $lesClients[$i];
    }

    $i++;
}

// Si aucun client trouvé, message d’erreur
if (!$client) {
    echo '<script> alert ("Client introuvable") </script>';
    header('Location: ../index.php');

}

// Si le formulaire est fait
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // on met à jour l'objet client avec les nouvelles valeurs
    $client->setNom($_POST['nom']);
    $client->setEmail($_POST['email']);
    $client->setTelephone($_POST['telephone']);
    $client->setAdresseRue($_POST['adresse_rue']);
    $client->setAdresseCp($_POST['adresse_cp']);
    $client->setAdresseVille($_POST['adresse_ville']);

    // on enregistre les modifications en BDD
    $clientDAO->update($client);

    // redirection vers la liste après modif
    header('Location: ListeClients.php');

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

    <!-- bouton retour liste -->
    <a href="Afficher_Clients.php"><button>Retour à la liste</button></a>

    <!-- formulaire pré-rempli avec les infos du client -->
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

        <!-- bouton d’envoi pour valider la modif -->
        <button type="submit">Modifier</button>
    </form>
</body>
</html>
