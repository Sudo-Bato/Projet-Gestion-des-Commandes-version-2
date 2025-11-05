<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../model/Client.php';

class ClientDAO {

    private $conn;

    // constructeur avec la base de donnée
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // récup les clients et les mettre dans un tableau
    public function afficherTous(): array {
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
    public function create(Client $unClient): void {

        //préparer la requete avec des parametre blind pr éviter les injection sql
        $sql = 'INSERT INTO clients (nom, email, telephone, adresse_rue, adresse_cp, adresse_ville) 
        VALUES (:nom, :email, :telephone, :adresse_rue, :adresse_cp, :adresse_ville)';

        $req = $this->conn->prepare($sql);

        $req->bindValue(':nom', $unClient->getNom());
        $req->bindValue(':email', $unClient->getEmail());
        $req->bindValue(':telephone', $unClient->getTelephone());
        $req->bindValue(':adresse_rue', $unClient->getAdresseRue());
        $req->bindValue(':adresse_cp', $unClient->getAdresseCp());
        $req->bindValue(':adresse_ville', $unClient->getAdresseVille());

        $req->execute();

    }


    // fonction pour supprimer un client
    public function delete($id) {
        $sql = 'DELETE FROM clients WHERE id=?';

        $req = $this->conn->prepare($sql);

        $req->execute([$id]);

    }

    // fonction pour modifier un client
    public function update(Client $unClient): void {
        $sql = 'UPDATE clients 
                SET nom = :nom, email = :email, telephone = :telephone,
                    adresse_rue = :adresse_rue, adresse_cp = :adresse_cp, adresse_ville = :adresse_ville
                WHERE id = :id';

        $req = $this->conn->prepare($sql);

        $req->bindValue(':nom', $unClient->getNom());
        $req->bindValue(':email', $unClient->getEmail());
        $req->bindValue(':telephone', $unClient->getTelephone());
        $req->bindValue(':adresse_rue', $unClient->getAdresseRue());
        $req->bindValue(':adresse_cp', $unClient->getAdresseCp());
        $req->bindValue(':adresse_ville', $unClient->getAdresseVille());
        $req->bindValue(':id', $unClient->getId());

        $req->execute();
    }


}
?>
