<?php 

include '../dao/ClientDAO.php'

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
        <a href="../index.php"><button>Retour à l'acceuil</button></a>
        <a href="./Ajout_Client.php"><button>Ajouter un Client</button></a>
    </div>

    <div id="tableau">
        <table>
            <tr>
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
                $clientDAO = new ClientDAO();
                $lesClients = $clientDAO->afficherTous();


                foreach ($lesClients as $unClient) {
                    echo "<tr>";
                    echo "<td>" . $unClient->getId() . "</td>";
                    echo "<td>" . $unClient->getNom() . "</td>";
                    echo "<td>" . $unClient->getEmail() . "</td>";
                    echo "<td>" . $unClient->getTelephone() . "</td>";
                    echo "<td>" . $unClient->getAdresseRue() . "</td>";
                    echo "<td>" . $unClient->getAdresseCp() . "</td>";
                    echo "<td>" . $unClient->getAdresseVille() . "</td>";
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