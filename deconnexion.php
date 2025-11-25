<?php
session_start();
session_destroy(); // On détruit toutes les infos de session
header('Location: login.php'); // Hop, retour au login
exit();
?>