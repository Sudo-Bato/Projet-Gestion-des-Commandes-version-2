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
        
        $req = "SELECT * FROM clients ORDER BY id ASC"; // id asc pr les mettre en ordre de l'id
        $stmt = $this->conn->prepare($req);

        // executer la requete
        $stmt->execute();

        // tableau des clients 
        $clients = [];

        // récupération des données de la requette sql 
        $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // pour chaques lignes récupéré, on fait un client et on met ses valeurs dedans
        foreach ($lignes as $laLigne) {
            $client = new Client();
            $client->setId($laLigne['id']);
            $client->setNom($laLigne['nom']);
            $client->setEmail($laLigne['email']);
            $client->setTelephone($laLigne['telephone']);
            $client->setAdresseRue($laLigne['adresse_rue']);
            $client->setAdresseCp($laLigne['adresse_cp']);
            $client->setAdresseVille($laLigne['adresse_ville']);

            // ajouter l'objet client dans le tableau
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

        // lier chaques valeurs du client aux paramètre de la requete préparé
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

        // requete pour suprrimer le client par rapport a son id
        $sql = 'DELETE FROM clients WHERE id=?';

        $req = $this->conn->prepare($sql);

        // executer la req avec l'id 
        $req->execute([$id]);

    }

    // fonction pour modifier un client
    public function update(Client $unClient): void {

        // mettre a jout le client en fonction de son id (en sql)
        $sql = 'UPDATE clients 
                SET nom = :nom, email = :email, telephone = :telephone,
                    adresse_rue = :adresse_rue, adresse_cp = :adresse_cp, adresse_ville = :adresse_ville
                WHERE id = :id';

        $req = $this->conn->prepare($sql);

        // lier les valeurs du client a la requete préparé
        $req->bindValue(':nom', $unClient->getNom());
        $req->bindValue(':email', $unClient->getEmail());
        $req->bindValue(':telephone', $unClient->getTelephone());
        $req->bindValue(':adresse_rue', $unClient->getAdresseRue());
        $req->bindValue(':adresse_cp', $unClient->getAdresseCp());
        $req->bindValue(':adresse_ville', $unClient->getAdresseVille());

        // l'id du client qu'on veut modifier
        $req->bindValue(':id', $unClient->getId());

        $req->execute();
    }

    // Fonction pour récupérer UN seul client par son ID
    public function getById($id): ?Client {
        $sql = "SELECT * FROM clients WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $laLigne = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$laLigne) {
            return null;
        }

        $client = new Client();
        $client->setId($laLigne['id']);
        $client->setNom($laLigne['nom']);
        $client->setEmail($laLigne['email']);
        $client->setTelephone($laLigne['telephone']);
        $client->setAdresseRue($laLigne['adresse_rue']);
        $client->setAdresseCp($laLigne['adresse_cp']);
        $client->setAdresseVille($laLigne['adresse_ville']);

        return $client;
    }


}
?>
