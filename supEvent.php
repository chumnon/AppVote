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
        if($_SESSION["connexion"] != true || $_SESSION["vote"] == true){
            $_SESSION["vote"] = false;
            $_SESSION["connexion"] = false;
            ?>
            <div class='container-fluid menuConnexion'>
                <div class='row blockCon'>
                    <h1 class='titleCon'>Vous n'êtes pas connecté</h1>
                    <div class='offset-3 col-6'>
                    <a href="connexion.php" class='linkCon'>Page de connexion</a>
                </div>
            </div>
            <?php
        } else {
            $servername = "localhost";
                $username = "root";
                $password = "root";
                $db = "appvote";
    
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
                    $id = -1;
                    echo "Erreur lors du chargement de la page"
                    $erreur = true;
            }

            $conn->query('SET NAMES utf8');
            $monEvent = "SELECT nom, id FROM evenement WHERE id LIKE '" . $id . "'";
            $lEvent = $conn->query($monEvent)->fetch_assoc();

            
            if ($_SERVER['REQUEST_METHOD'] != "POST"){
                ECHO $lEvent["id"];
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="container-fluid menu">
                <div class="row warning">
                    <h1>ATTENTION, VOUS ÊTES SUR LE POINT DE SUPPRIMER L'ÉVÈNEMENT SUIVANT:</h1>
                    <h1><?php echo $lEvent['nom']?></h1>
                    <h1>ÊTES-VOUS SÛR DE VOULOIR LE SUPPRIMER?</h1>
                </div>
                <div class="row confirm">
                    <div class="col-6 offset-md-2 col-md-3">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <input type="submit" value="oui">
                        <input type="hidden" name="id" value="<?php echo $id;?>">
                    <form>
                    </div>
                    <div class="col-6 offset-md-2 col-md-3">
                        <a href="unEvent.php?id=<?php echo $lEvent['id']?>">non</a>
                    </div>
                </div>
            </div>
            </form>
            <?php
            } else {
                echo  $lEvent['id'];
                $destroyDroit = "DELETE FROM gestion WHERE evenement LIKE '" . $lEvent['id'] . "'";
                $destroyVote = "DELETE FROM vote WHERE evenementID LIKE '" . $lEvent['id'] . "'";
                $destroy = "DELETE FROM evenement WHERE id LIKE '" . $lEvent['id'] . "'";
                if ($conn->query($destroyDroit) === TRUE) {
                    if($conn->query($destroyVote) === TRUE) {
                        if ($conn->query($destroy) === TRUE) {
                            echo $destroyDroit;
                            ?>
                            <div class="container-fluid menu">
                                <h1>L'évènement a bien été supprimer</h1>
                                <a href="index.php">Page d'accueil</a>
                            </div>
                            <?php
                        } else {
                            ?><h1><?php echo "Error: " . $destroy . "<br>" . $conn->error; ?></h1>
                            <a class="optionBar" href="index.php">page principal</a>
                            <?php
                        } 
                    } else {
                        ?><h1><?php echo "Error: " . $destroyVote . "<br>" . $conn->error; ?></h1>
                        <a class="optionBar" href="index.php">page principal</a>
                        <?php
                    }
                } else {
                    ?><h1><?php echo "Error: " . $destroyDroit . "<br>" . $conn->error; ?></h1>
                    <a class="optionBar" href="index.php">page principal</a>
                    <?php
                }
            }
        $conn->close();
        }
    ?>

</body>
</html>