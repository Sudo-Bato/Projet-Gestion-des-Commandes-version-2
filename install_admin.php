<?php
require_once 'database.php';

$db = new Database();
$conn = $db->getConnection();

// Le mot de passe qu'on veut
$password = "admin123";

// On le hache (on le crypte) pour qu'il soit sécurisé
$hash = password_hash($password, PASSWORD_BCRYPT);

try {
    $sql = "INSERT INTO utilisateurs (login, mot_de_passe, role) VALUES ('admin', :mdp, 'admin')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':mdp' => $hash]);
    
    echo "<h1>Succès !</h1>";
    echo "<p>L'utilisateur <strong>admin</strong> a été créé avec le mot de passe <strong>admin123</strong>.</p>";
    echo "<a href='login.php'>Clique ici pour te connecter</a>";
    
} catch (PDOException $e) {
    echo "<h1>Erreur</h1>";
    echo "Impossible de créer l'admin (il existe peut-être déjà ?) : " . $e->getMessage();
}
?>