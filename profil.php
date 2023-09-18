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
    <title>Profil</title>

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

            $conn->query('SET NAMES utf8');
            $conn->query('SET NAMES utf8');
            $user = $_SESSION['user'];
            $monUser = "SELECT user, id FROM utilisateur WHERE user LIKE '" . $user . "'";
            $listeEvent = "SELECT nom, id FROM evenement WHERE id IN (SELECT evenement FROM gestion WHERE user IN (SELECT id FROM utilisateur WHERE user LIKE '" . $user . "'))";
            $result = $conn->query($listeEvent);
            $leUser = $conn->query($monUser)->fetch_assoc();

            ?>
            <div class="container-fluid menu">
                <div class="row retour">
                    <div class="col-3 offset-md-9">
                        <a href="index.php">Page principal</a>
                    </div>
                </div>
                <div class="row leUser">
                    <h1><?php echo $leUser['user']?></h1>
                </div>
                <div class="row selectEvent">
                    <div class= "offset-md-2 col-md-8" >
                        <h2>Mes évènements</h2>
                        <?php
                        if ($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                ?>
                                <a href="modEvent.php?id=<?php echo $row["id"]?>"><?php echo $row['nom']?></a>
                                </br>
                                <?php
                            }
                        } else {
                            ?>
                            <h3>Aucun évènement</h3>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row modUser">
                    <div class="col-6 offset-md-2 col-md-3">
                        <a href="modUser.php?id=<?php echo $leUser['id']?>">modifier le compte</a>
                    </div>
                    <div class="col-6 offset-md-2 col-md-3">
                        <a href="supUser.php?id=<?php echo $leUser['id']?>">supprimer le compte</a>
                    </div>
                </div>
            </div>
        <?php       
        $conn->close();
        }
    ?>

</body>
</html>