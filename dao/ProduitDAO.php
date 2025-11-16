<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../model/Produit.php'; //

class ProduitDAO {

    private $conn;

    // constructeur avec la base de donnée
    public function __construct() {
        $database = new Database(); 
        $this->conn = $database->getConnection(); 
    }

    // récup les produits et les mettre dans un tableau
    public function afficherTous(): array {

        // requete sql pr recup tous les produits et les trier par id
        $req = "SELECT * FROM produits ORDER BY id ASC";
        $stmt = $this->conn->prepare($req);
        $stmt->execute();

        // tableau des produits
        $produits = [];

        // recup les données de la bdd
        $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // pour chaques lignes on crée un objet produit et on met toutes ses infos
        foreach ($lignes as $laLigne) {
            $produit = new Produit();
            $produit->setId($laLigne['id']);
            $produit->setNom($laLigne['nom']);
            $produit->setDescription($laLigne['description']);
            $produit->setPrix($laLigne['prix']);
            $produit->setStock($laLigne['stock']);

            // ajouter l'objet produit au tableau
            $produits[] = $produit;
        }

        return $produits;
    }

    // fonction pour ajouter un produit
    public function create(Produit $unProduit): void {

        // requete sql avec paramètre blind pr éviter injection sql
        $sql = 'INSERT INTO produits (nom, description, prix, stock) 
                VALUES (:nom, :description, :prix, :stock)';

        $req = $this->conn->prepare($sql);

        // lier chaques valeurs du produit a la requete préparé
        $req->bindValue(':nom', $unProduit->getNom());
        $req->bindValue(':description', $unProduit->getDescription());
        $req->bindValue(':prix', $unProduit->getPrix());
        $req->bindValue(':stock', $unProduit->getStock());

        $req->execute();
    }

    // fonction pour supprimer un produit
    public function delete($id) {

        // requete sql pour supp un produit avec son id
        $sql = 'DELETE FROM produits WHERE id=?';

        $req = $this->conn->prepare($sql);

        // executer la req avec l'id envoyé
        $req->execute([$id]);
    }

    // fonction pour modifier un produit
    public function update(Produit $unProduit): void {

        // mettre a jout les infos du produit en fonction de son id
        $sql = 'UPDATE produits 
                SET nom = :nom, description = :description, prix = :prix, stock = :stock
                WHERE id = :id';

        $req = $this->conn->prepare($sql);

        // lier les valeurs a la requete préparé
        $req->bindValue(':nom', $unProduit->getNom());
        $req->bindValue(':description', $unProduit->getDescription());
        $req->bindValue(':prix', $unProduit->getPrix());
        $req->bindValue(':stock', $unProduit->getStock());

        // l'id du produit qu'on veut modifier
        $req->bindValue(':id', $unProduit->getId());

        $req->execute();
    }

    // Fonction pour récupérer UN seul produit par son ID
    public function getById($id): ?Produit {

        // recup le produit qui a cet id
        $sql = "SELECT * FROM produits WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        // executer la req avec l'id
        $stmt->execute([$id]);
        
        // recup la ligne du produit
        $laLigne = $stmt->fetch(PDO::FETCH_ASSOC);

        // si pas trouvé on retourne null
        if (!$laLigne) {
            return null;
        }

        // sinon on crée l'objet produit et on met tt ses valeurs
        $produit = new Produit();
        $produit->setId($laLigne['id']);
        $produit->setNom($laLigne['nom']);
        $produit->setDescription($laLigne['description']);
        $produit->setPrix($laLigne['prix']);
        $produit->setStock($laLigne['stock']);

        // on retourne l'objet crée en métier.
        return $produit;
    }
}
?>