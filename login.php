<?php
session_start(); // OBLIGATOIRE : toujours en tout premier !

require_once 'dao/UserDAO.php';

$message = "";

// Si le formulaire est envoyé
if (isset($_POST['valider'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $userDAO = new UserDAO();
    $utilisateur = $userDAO->seConnecter($login, $password);

    if ($utilisateur) {
        // SUCCÈS : On enregistre les infos dans la session
        $_SESSION['user_id'] = $utilisateur->getId();
        $_SESSION['user_login'] = $utilisateur->getLogin();
        $_SESSION['user_role'] = $utilisateur->getRole();

        // On redirige vers l'accueil
        header('Location: index.php');
        exit();
    } else {
        $message = "Mauvais login ou mot de passe !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh; flex-direction:column;">

    <h1>Connexion</h1>

    <?php if ($message): ?>
        <p style="color: red; font-weight:bold;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" style="border: 1px solid #ccc; padding: 20px; border-radius: 10px;">
        <div style="margin-bottom: 10px;">
            <label>Login :</label><br>
            <input type="text" name="login" required placeholder="Votre login">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Mot de passe :</label><br>
            <input type="password" name="password" required placeholder="Votre mot de passe">
        </div>

        <button type="submit" name="valider">Se connecter</button>
    </form>

</body>
</html>