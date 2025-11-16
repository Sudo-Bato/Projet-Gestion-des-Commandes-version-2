<?php
require_once '../dao/ClientDAO.php';
require_once '../model/Client.php';

// si on a cliqué sur le bouton ajouter
if (isset($_POST['ajouter'])) {

    // créer un objet client
    $client = new Client();

    // mettre les valeurs du formulaire dans l'objet client
    $client->setNom($_POST['nom']);
    $client->setEmail($_POST['email']);
    $client->setTelephone($_POST['telephone']);
    $client->setAdresseRue($_POST['adresse_rue']);
    $client->setAdresseCp($_POST['adresse_cp']);
    $client->setAdresseVille($_POST['adresse_ville']);

    // créer le DAO pour pouvoir utiliser la fonction create qui vas ajouter ce client dans la bdd
    $clientDAO = new ClientDAO();

    // ajouter le client dans la bdd
    $clientDAO->create($client);

    // Rediriger vers la liste après ajout 
    header('Location: ListeClients.php');

}
?>

<!-- formulaire pour ajouter un client -->
<form method="POST" action="">

    <input type="text" name="nom" placeholder="Nom"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <input type="text" name="telephone" placeholder="Téléphone"><br>
    <input type="text" name="adresse_rue" placeholder="Adresse"><br>
    <input type="text" name="adresse_cp" placeholder="Code Postal"><br>
    <input type="text" name="adresse_ville" placeholder="Ville"><br>
    <input type="submit" name="ajouter" value="Ajouter">

</form>
