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
            $leUser = $conn->query($monUser)->fetch_assoc();

            
            if ($_SERVER['REQUEST_METHOD'] != "POST"){
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="container-fluid menuSup">
                <div class="row blockSup">
                    <h1 class="warningSup">ATTENTION, VOUS ÊTES SUR LE POINT DE SUPPRIMER LE COMPTE SUIVANT:</h1>
                    <h1 class="titreSup"><?php echo $leUser['user']?></h1>
                    <h1 class="warningSup">ÊTES-VOUS SÛR DE VOULOIR LE SUPPRIMER?</h1>
                    </br>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="row blockBtnSup">
                            <a href="profil.php?id=<?php echo $leUser['id']?>" class="btnNonSup">non</a>
                            <input type="submit" value="oui" class="btnSup">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $leUser['id'];?>">
                    <form>
                </div>
            </div>
            </form>
            <?php
            } else {
                $destroyDroit = "DELETE FROM gestion WHERE user LIKE '" . $leUser['id'] . "'";
                $destroy = "DELETE FROM utilisateur WHERE id LIKE '" . $leUser['id'] . "'";
                if ($conn->query($destroyDroit) === TRUE) {
                    if ($conn->query($destroy) === TRUE) {
                        ?>
                        <div class="container-fluid menu">
                            <h1>Le compte a bien été supprimer</h1>
                            <a href="connection.php">Page de connection</a>
                        </div>
                        <?php
                    } else {
                        ?><h1><?php echo "Error: " . $destroy . "<br>" . $conn->error; ?></h1>
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