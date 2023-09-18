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
            $user = $_SESSION['user'];
            $monUser = "SELECT user, id FROM utilisateur WHERE user LIKE '" . $user . "'";
            $leUser = $conn->query($monUser)->fetch_assoc();

            
            if ($_SERVER['REQUEST_METHOD'] != "POST"){
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="container-fluid menu">
                <div class="row warning">
                    <h1>ATTENTION, VOUS ÊTES SUR LE POINT DE SUPPRIMER LE COMPTE SUIVANT:</h1>
                    <h1><?php echo $leUser['user']?></h1>
                    <h1>ÊTES-VOUS SÛR DE VOULOIR LE SUPPRIMER?</h1>
                </div>
                <div class="row confirm">
                    <div class="col-6 offset-md-2 col-md-3">
                        <input type="submit" value="oui">
                    </div>
                    <div class="col-6 offset-md-2 col-md-3">
                        <a href="profil.php?id=<?php echo $leUser['id']?>">non</a>
                    </div>
                </div>
            </div>
            </form>
            <?php
            } else {
                $destroy = "DELETE FROM utilisateur WHERE id = " . $leUser['id'];
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
            } 
        $conn->close();
        }
    ?>

</body>
</html>