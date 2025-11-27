<?php 
session_start();
// Si l'utilisateur n'est PAS connecté (pas d'id dans la session)
if (!isset($_SESSION['user_id'])) {
    // On le redirige vers le login
    header('Location: ../login.php'); 
    exit();
}

include '../dao/ClientDAO.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <h1>Gestion des Clients</h1>

    <div id="top-section">

        <!-- bouton pour revenir a l'accueil -->
        <a href="../index.php"><button>Retour à l'acceuil</button></a>

        <!-- bouton pour aller au formulaire d'ajout -->
        <a href="./Ajout_Client.php"><button>Ajouter un Client</button></a>

    </div>

    <div id="tableau">
        <table>
            <tr>
                <!-- en-têtes du tableau -->
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Adresse Rue</th>
                <th>Code Postal</th>
                <th>Ville</th>
                <th>Actions</th>
            </tr>

            <?php
                // créer le DAO pr recup les clients
                $clientDAO = new ClientDAO();

                // recup tous les clients sous forme d'un tableau d'objets
                $lesClients = $clientDAO->afficherTous();

                // parcourir la liste et afficher chaque client dans le tableau HTML
                foreach ($lesClients as $unClient) {
                    echo "<tr>";
                    echo "<td>" . $unClient->getId() . "</td>";
                    echo "<td>" . $unClient->getNom() . "</td>";
                    echo "<td>" . $unClient->getEmail() . "</td>";
                    echo "<td>" . $unClient->getTelephone() . "</td>";
                    echo "<td>" . $unClient->getAdresseRue() . "</td>";
                    echo "<td>" . $unClient->getAdresseCp() . "</td>";
                    echo "<td>" . $unClient->getAdresseVille() . "</td>";

                    // boutons modifier et supprimer
                    // et renvoyer vers la page pour avec l'id du client
                    echo "<td>
                            <a href='Modifier_Client.php?id=" . $unClient->getId() . "'><button>Modifier</button></a> 
                            <a href='../supprimerClient.php?id=" . $unClient->getId() . "'><button>Supprimer</button></a>
                        </td>";

                    echo "</tr>";
                }
            ?>
        </table>
    </div>
       

</body>
</html>
