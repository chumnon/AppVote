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
            $monEvent = "SELECT nom, id FROM evenement WHERE id LIKE '" . $id . "'";
            $lEvent = $conn->query($monEvent)->fetch_assoc();


        $conn->close();
        }
    ?>

</body>
</html>