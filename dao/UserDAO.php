<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../model/Utilisateur.php';

class UserDAO {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Fonction pour vérifier la connexion
    public function seConnecter($login, $password): ?Utilisateur {
        
        // 1. On cherche l'utilisateur par son login
        $sql = "SELECT * FROM utilisateurs WHERE login = :login";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':login', $login);
        $stmt->execute();
        
        $ligne = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Si on a trouvé quelqu'un, on vérifie le mot de passe
        if ($ligne) {
            // password_verify compare le mot de passe clair (ex: 'admin123') 
            // avec le haché bizarre dans la BDD
            if (password_verify($password, $ligne['mot_de_passe'])) {
                // C'est bon ! On retourne l'objet Utilisateur
                return new Utilisateur($ligne['id'], $ligne['login'], $ligne['role']);
            }
        }

        // Si login pas trouvé ou mot de passe faux
        return null;
    }
}
?>