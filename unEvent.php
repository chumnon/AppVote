<?php 
session_start();
if (isset($_SESSION["connexion"])){
} else {
    $_SESSION["connexion"] = false;
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évènements</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="style/style.css" rel="stylesheet">
</head>
<body>
    <?php
        if($_SESSION["connexion"] != true){
            ?>
            <h1>Vous n'êtes pas connecté</h1>
            <a href="connection.php">Page de connection</a>
            <?php
        } else {
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $db = "appvote";
    
            //Connection
            $conn = new mysqli($servername, $username,$password,$db);
    
            //Verrification
            if ($conn ->connect_error){
                die("Erreur de connection: " . $conn->connect_error);
            }

            if (isset($_GET['id'])){
                $id = $_GET['id'];
            } else if (isset ($_POST['id'])){
                $id = $_POST['id'];
            } else {
                    $idErreur = "Erreur chargement de la page";
                    $erreur = true;
            }

            $conn->query('SET NAMES utf8');
            $monEvent = "SELECT nom, date, lieu, departement, description, id FROM evenement WHERE id LIKE '" . $id . "'";
            $listeUser = "SELECT user , id FROM utilisateur WHERE id IN (SELECT user FROM gestion WHERE evenement IN (SELECT id FROM evenement WHERE id LIKE '" . $id . "'))";
            $result = $conn->query($listeUser);
            $lEvent = $conn->query($monEvent)->fetch_assoc();

            ?>
            <div class="container-fluid menu">
                <div class="row retour">
                    <div class="col-3 offset-md-9">
                        <a href="index.php">Page principal</a>
                    </div>
                </div>
                <div class="row lEvent">
                    <h1><?php echo $lEvent['nom']?></h1>
                </div>
                <div class="row">
                    <p>Date: <?php echo $lEvent['date']?></p>
                </div>
                <div class="row">
                    <p>Lieu: <?php echo $lEvent['lieu']?></p>
                </div>
                <div class="row">
                    <p>Departement: <?php echo $lEvent['departement']?></p>
                </div>
                <div class="row">
                    <p>Description: </p> </br>
                    <p><?php echo $lEvent['description']?></p>
                </div>

                <div class="row gerant">
                    <div class= "offset-md-2 col-md-8" >
                        <h2>Les gestionnaire:</h2>
                        <p>
                        <?php
                        if ($result-> num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                echo $row["user"]  ?> <?php
                            }
                        } else {
                            ?>
                            <h3>Aucun gestionnaire</h3>
                            <?php
                        }
                        ?>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <a href="modEvent.php?id=<?php echo $lEvent["id"]?>">modifier l'évènement</a>
                </div>
            </div>
        <?php       
        $conn->close();
        }
    ?>

</body>
</html>