<?php
require_once __DIR__ . '/../database.php';   // connexion à la BDD
require_once __DIR__ . '/../model/Commande.php'; // modèle Commande

class CommandeDAO {

    private $conn;

    public function __construct() {
        // créer l'accès à la base via Database()
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Récupérer toutes les commandes
    public function afficherTous(): array {
        // requête pour ramener toutes les commandes (les + récentes d'abord)
        $sql = "SELECT * FROM commandes ORDER BY date_commande DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $commandes = [];
        $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC); // tableau associatif

        // transformer chaque ligne SQL → objet Commande
        foreach ($lignes as $ligne) {
            $commande = new Commande();
            $commande->setId($ligne['id']);
            $commande->setClientId($ligne['client_id']);
            $commande->setDateCommande($ligne['date_commande']);
            $commande->setStatut($ligne['statut']);
            $commandes[] = $commande;
        }

        return $commandes;
    }

    // Récupérer les produits d’une commande (pour affichage détaillé)
    public function getDetailsCommande($commandeId) {

        // jointure entre details_commandes et produits
        $sql = "SELECT p.nom, p.prix, d.quantite 
                FROM details_commandes d
                JOIN produits p ON d.produit_id = p.id
                WHERE d.commande_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $commandeId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // tableau de détails
    }
    
    // Créer une commande + enlever le stock (transaction pour sécuriser)
    public function create($client_id, $panier) {
        try {
            // on démarre une transaction (si un truc foire → rollback)
            $this->conn->beginTransaction();

            // 1️⃣ création de la commande principale
            $sql = "INSERT INTO commandes (client_id, date_commande, statut)
                    VALUES (:client_id, NOW(), 'en cours')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':client_id', $client_id);
            $stmt->execute();

            // récupérer l'id de la nouvelle commande
            $commande_id = $this->conn->lastInsertId();

            // préparations des requêtes utilisées dans la boucle
            $sqlDetail = "INSERT INTO details_commandes (commande_id, produit_id, quantite)
                          VALUES (:commande_id, :produit_id, :quantite)";
            $stmtDetail = $this->conn->prepare($sqlDetail);

            // vérifier stock actuel
            $sqlCheck = "SELECT stock, nom FROM produits WHERE id = :id";
            $stmtCheck = $this->conn->prepare($sqlCheck);

            // mettre à jour le stock
            $sqlUpdateStock = "UPDATE produits SET stock = stock - :qte WHERE id = :id";
            $stmtUpdateStock = $this->conn->prepare($sqlUpdateStock);

            // 2️⃣ Parcourir le panier (chaque produit commandé)
            foreach ($panier as $ligne) {
                $id_prod = $ligne['id_produit'];
                $qte_demandee = $ligne['qte'];

                // A — vérifier que le stock est suffisant
                $stmtCheck->execute([':id' => $id_prod]);
                $produit = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($produit['stock'] < $qte_demandee) {
                    // si stock insuffisant → on stoppe tout
                    throw new Exception("Stock insuffisant pour le produit : " . $produit['nom']);
                }

                // B — insérer la ligne de détail dans la commande
                $stmtDetail->bindValue(':commande_id', $commande_id);
                $stmtDetail->bindValue(':produit_id', $id_prod);
                $stmtDetail->bindValue(':quantite', $qte_demandee);
                $stmtDetail->execute();

                // C — mise à jour du stock
                $stmtUpdateStock->bindValue(':qte', $qte_demandee);
                $stmtUpdateStock->bindValue(':id', $id_prod);
                $stmtUpdateStock->execute();
            }

            // si tout est ok → validation de la transaction
            $this->conn->commit();

        } catch (Exception $e) {
            // en cas de problème → on annule tout
            $this->conn->rollBack();
            echo "<script>alert('Erreur : " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
            exit();
        }
    }

    // Supprimer une commande + remettre le stock (le contraire du create)
    public function delete($id_commande) {
        try {
            $this->conn->beginTransaction();

            // récupérer les produits de la commande (quantité + id produit)
            $sqlDetails = "SELECT produit_id, quantite FROM details_commandes WHERE commande_id = :id";
            $stmtDetails = $this->conn->prepare($sqlDetails);
            $stmtDetails->execute([':id' => $id_commande]);
            $lignes = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

            // requête pour rendre le stock
            $sqlUpdateStock = "UPDATE produits SET stock = stock + :qte WHERE id = :id";
            $stmtUpdateStock = $this->conn->prepare($sqlUpdateStock);

            // remettre chaque produit en stock
            foreach ($lignes as $ligne) {
                $stmtUpdateStock->bindValue(':qte', $ligne['quantite']);
                $stmtUpdateStock->bindValue(':id', $ligne['produit_id']);
                $stmtUpdateStock->execute();
            }

            // supprimer la commande
            $sqlDelete = "DELETE FROM commandes WHERE id = :id";
            $stmtDelete = $this->conn->prepare($sqlDelete);
            $stmtDelete->execute([':id' => $id_commande]);

            $this->conn->commit();

        } catch (Exception $e) {
            $this->conn->rollBack();
            echo "Erreur lors de la suppression : " . $e->getMessage();
        }
    }

    // récupérer une commande via son id
    public function getById($id): ?Commande {
        $sql = "SELECT * FROM commandes WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $ligne = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ligne) {
            $cmd = new Commande();
            $cmd->setId($ligne['id']);
            $cmd->setClientId($ligne['client_id']);
            $cmd->setDateCommande($ligne['date_commande']);
            $cmd->setStatut($ligne['statut']);
            return $cmd;
        }
        return null;
    }

    // mettre à jour uniquement le statut d'une commande
    public function updateStatut($id, $nouveauStatut) {
        $sql = "UPDATE commandes SET statut = :statut WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':statut', $nouveauStatut);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
?>
