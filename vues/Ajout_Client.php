<?php
require_once '../dao/ClientDAO.php';
require_once '../model/Client.php';

if (isset($_POST['ajouter'])) {
    $client = new Client();
    $client->setNom($_POST['nom']);
    $client->setEmail($_POST['email']);
    $client->setTelephone($_POST['telephone']);
    $client->setAdresseRue($_POST['adresse_rue']);
    $client->setAdresseCp($_POST['adresse_cp']);
    $client->setAdresseVille($_POST['adresse_ville']);

    $clientDAO = new ClientDAO();
    $clientDAO->create($client);

}
?>

<form method="POST" action="">
    <input type="text" name="nom" placeholder="Nom"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <input type="text" name="telephone" placeholder="Téléphone"><br>
    <input type="text" name="adresse_rue" placeholder="Adresse"><br>
    <input type="text" name="adresse_cp" placeholder="Code Postal"><br>
    <input type="text" name="adresse_ville" placeholder="Ville"><br>
    <input type="submit" name="ajouter" value="Ajouter">
</form>
