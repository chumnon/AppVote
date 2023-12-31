<?php 
session_start();
if (isset($_SESSION["connexion"])){
} else {
    $_SESSION["connexion"] = false;
};
?>

<!--Un projet d'Arthur Lamothe, M-NAV-->
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
        if($_SESSION["connexion"] != true || $_SESSION["vote"] == true){
            $_SESSION["vote"] = false;
            $_SESSION["connexion"] = false;
            ?>
            <div class='container-fluid menuConnexion'>
                <div class='row blockCon'>
                    <h1 class='titleCon'>Vous n'êtes pas connecté</h1>
                    <div class='offset-3 col-6 linkConBox'>
                    <a href="connexion.php" class='linkCon'>Page de connexion</a>
                </div>
            </div>
            <?php
        } else {
            $servername = "localhost";
                $username = "root";
                $password = "root";
                $db = "m-nav";
    
                //Connexion
                $conn = new mysqli($servername, $username,$password,$db);
    
                //Verrification
                if ($conn ->connect_error){
                    die("Erreur de connexion: " . $conn->connect_error);
                }

            $conn->query('SET NAMES utf8');
            $user = $_SESSION['user'];
            $monUser = "SELECT user, id FROM utilisateur WHERE id LIKE '" . $user . "'";
            $listeEvent = "SELECT nom, id FROM evenement WHERE id IN (SELECT evenement FROM gestion WHERE user IN (SELECT id FROM utilisateur WHERE id LIKE '" . $user . "'))";
            $result = $conn->query($listeEvent);
            $leUser = $conn->query($monUser)->fetch_assoc();

            ?>

            <div class="container-fluid banniere banniereInfo">
                <div class="row navBar navBarInfo">
                        <h1 class="appLogoAlt appLogoAltInfo">M-NAV</h1>
                        <h1 class="leUser navBarTitre navBarTitreInfo"><?php echo $leUser['user']?></h1>
                        <a class="navBarOption navBarOptionInfo" href="index.php">Page principal</a>
                </div>
            </div>

            <div class="container-fluid menuInfo">
                <div class="row modUser">
                    <a href="modUser.php" class="col-6 offset-md-2 col-md-4 uneOptionProfil">modifier le compte</a>
                    <a href="supUser.php?id=<?php echo $leUser['id']?>" class="col-6 col-md-4  uneOptionProfil">supprimer le compte</a>
                </div>

                <div class="row selectEvent">
                    <div class= "offset-md-2 col-md-8" >
                        <h2 class='titreInfo'>Mes évènements</h2>
                        <?php
                        if ($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                ?>
                                <a href="unEvent.php?id=<?php echo $row["id"]?>" class='unEvent'><?php echo $row['nom']?></a>
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
            </div>
        <?php       
        $conn->close();
        }
    ?>

</body>
</html>