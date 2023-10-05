<!--Un projet d'Arthur Lamothe, M-NAV-->
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
            <div class="container-fluid banniere banniereInfo">
                <div class="row navBar navBarInfo">
                        <h1 class="appLogoAlt appLogoAltInfo">M-NAV</h1>
                        <h1 class="leUser navBarTitre navBarTitreInfo"><?php echo $lEvent['nom']?></h1>
                        <a class="navBarOption navBarOptionInfo" href="index.php">Page principal</a>
                </div>
            </div>

            <div class="container-fluid menuInfo">
                <div class="row boxInfoEvent">
                    <p class="catEventInfo">Date: <a class="eventInfo"><?php echo $lEvent['date']?></a></p>
                </div>
                <div class="row boxInfoEvent">
                    <p class="catEventInfo">Lieu: <a class="eventInfo"><?php echo $lEvent['lieu']?></a></p>
                </div>
                <div class="row boxInfoEvent">
                    <p class="catEventInfo">Departement: <a class="eventInfo"><?php echo $lEvent['departement']?></a></p>
                </div>
                <div class="row boxInfoEvent">
                    <p class="catEventInfo">Description: </p>
                </div>
                <div class="row boxInfoEventAlt">
                    <a class="eventDescription"><?php echo $lEvent['description']?></a>
                </div>

                <div class="row">
                    <div class= "offset-md-2 col-md-8" >
                        <h2 class= "titreInfo">Les gestionnaire:</h2>
                        <p class= "unUser">--
                        <?php
                        if ($result-> num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                echo "~" . $row["user"] . "~" ?> <?php
                            }
                        } else {
                            ?>
                            <h3>Aucun gestionnaire</h3>
                            <?php
                        }
                        ?>
                        --</p>
                    </div>
                </div>

                <div class="row modEvent">
                    <a href="modEvent.php?id=<?php echo $lEvent["id"]?>" class="uneOptionEvent">modifier l'évènement</a>
                    <a href="supEvent.php?id=<?php echo $lEvent["id"]?>" class="uneOptionEvent">supprimer l'évènement</a>
                </div>
            </div>
        <?php       
        $conn->close();
        }
    ?>

</body>
</html>