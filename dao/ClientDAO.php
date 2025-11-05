<?php
require_once '../database.php';
require_once '../model/Client.php';

class ClientDAO {

    private $conn;

    // constructeur avec la base de donnée
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // récup les clients et les mettre dans un tableau
    public function getToutLesClients() {
        $req = "SELECT * FROM clients ORDER BY id ASC";
        $stmt = $this->conn->prepare($req);
        $stmt->execute();

        $clients = [];

        $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($lignes as $laLigne) {
            $client = new Client();
            $client->setId($laLigne['id']);
            $client->setNom($laLigne['nom']);
            $client->setEmail($laLigne['email']);
            $client->setTelephone($laLigne['telephone']);
            $client->setAdresseRue($laLigne['adresse_rue']);
            $client->setAdresseCp($laLigne['adresse_cp']);
            $client->setAdresseVille($laLigne['adresse_ville']);
            $clients[] = $client;
        }

        return $clients;
    }


    // fonction pour ajouter un client
    public function ajouterUnClient() {

    }


}
?>
