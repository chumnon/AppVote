<?php 
session_start();
if (isset($_SESSION["connexion"])){
} else {
    $_SESSION["connexion"] = false
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppVote</title>

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
            $sql = "SELECT nom, id FROM evenement";
            $result = $conn->query($sql);
            ?>
            <div class="container-fluid menu">
                <div class="row optionCon">
                    <div class="offset-md-8 col-6 col-md-2">
                        <a href="connection.php">Changer utilisateur</a>
                    </div>
                    <div class="col-6 col-md-2">
                        <a href="newUser.php">Nouvel utilisateur</a>
                    </div>
                </div>
                <div class="row selectEvent">
                    <div class= "offset-md-2 col-md-8" >
                        <h2>Choix d'évènement</h2>
                        <select class="choixEvent">
                            <?php
                            if ($result->num_rows > 0){
                                while($row = $result->fetch_assoc()){
                                    ?>
                                    <option value="<?php echo $row['id']?>"><?php echo $row['nom']?>
                                    <?php
                                }
                            } else {
                                ?>
                                <option value="-1">Aucun évènement</option>
                                <?php
                            }
                            ?>
                        <select>
                    </div>
                </div>
                <div class="row optionEvent ">
                    <div class="offset-md-1 col-md-4 col-6">
                        <a class="modEvent" href="modEvent.php?id=<?php echo $row['id']?>">Modifier l'évènement</a>
                    </div>
                    <div class="offset-md-2 col-md-4 col-6">
                        <a class="addEvent" href="newEvent.php">Ajouter un évènement</a>
                    </div> 
                </div>
                <div class="row optionVote">
                    <div class="col-4">
                        <a class="voteP" href="voteP.php?id=<?php echo $row['id']?>">Vote participant</a>
                    </div>
                    <div class="col-4">
                        <a class="voteO" href="voteO.php?id=<?php echo $row['id']?>">Vote organisateur</a>
                    </div>
                    <div class="col-4">
                        <a class="result" href="showVote.php?id=<?php echo $row['id']?>">Voir résultat</a>
                    </div>
                </div>
            </div>
        <?php        
    }
    $conn->close();
    ?>
</body>
</html>