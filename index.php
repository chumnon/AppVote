<?php 
session_start();
if (isset($_SESSION["connexion"] )){
} else {
    $_SESSION["connexion"] = false;
};
if (isset($_SESSION["vote"] )){
} else {
    $_SESSION["vote"] = false;
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
    if($_SESSION["connexion"] != true || $_SESSION["vote"] == true){
        $_SESSION["vote"] = false;
        $_SESSION["connexion"] = false;
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
            $monUser = "SELECT user, id FROM utilisateur WHERE id LIKE '" . $user . "'";
            $listeEvent = "SELECT nom, id FROM evenement WHERE id IN (SELECT evenement FROM gestion WHERE user IN (SELECT id FROM utilisateur WHERE id LIKE '" . $user . "'))";
            $result = $conn->query($listeEvent);
            $leUser = $conn->query($monUser)->fetch_assoc();
            ?>
            
            <div class="container-fluid banniere">
            <div class="row navBar">
                    <div class="col-4 col-md-2 monProfil">
                        <a class="navBarOption" href="profil.php?id=<?php echo $leUser['id']?>">Mon profil</a>
                    </div>
                    <div class="offset-md-5 col-4 col-md-3 changeUser">
                        <a class="navBarOption" href="connection.php">Changer utilisateur</a>
                    </div>
                    <div class="col-4 col-md-2">
                        <a class="navBarOption" href="newUser.php">Nouvel utilisateur</a>
                    </div>
                </div>
                <div class="row logo">
                <div class="offset-3 col-6">
                    <h1 class="appLogo">M-NAV</h1>
                    </div>
                </div>
            </div>

            <div class="container-fluid menu">
                
                <div class="row selectEvent">
                    <div class= "offset-md-2 col-md-8" >
                        <h2 class="titre">Choix d'évènement</h2>
                        <select class="choixEvent" id="listeEvent">
                            <?php
                            if ($result->num_rows > 0){
                                while($row = $result->fetch_assoc()){
                                    $noEvent = false;
                                    if (isset($option1)){
                                    } else {
                                        $option1 = $row['id'];
                                    };
                                    ?>
                                    <option class="unOptionEvent" value="<?php echo $row['id']?>"><?php echo $row['nom']?>
                                    <?php
                                }
                            } else {
                                $noEvent = true;
                                ?>
                                <option value="-1">Aucun évènement</option>
                                <?php
                            }
                            ?>
                        <select>
                    </div>
                </div>
                <?php if ($noEvent != true){
                ?>
                <div class="row optionEvent">
                    <a class="offset-md-1 col-md-4 col-6 modEvent uneOptionIndex" href="unEvent.php?id=<?php echo $option1?>">
                        Info de l'évènement
                    </a>
                    <a class="offset-md-2 col-md-4 col-6 addEvent uneOptionIndex" href="newEvent.php">
                        Ajouter un évènement
                    </a> 
                </div>
                <div class="row optionVote">
                    <a class="voteP uneOptionIndex" href="voteP.php?id=<?php echo $option1?>">
                        Vote participant
                    </a>
                    <a class="voteO uneOptionIndex" href="voteO.php?id=<?php echo $option1?>">
                        Vote organisateur
                    </a>
                    <a class="result uneOptionIndex" href="showVote.php?id=<?php echo $option1?>">
                        Voir résultat
                    </a>
                </div>
                <?php
                }
                ?>
            </div>
        <?php       
    $conn->close(); 
    }
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            var listeEvent = $("#listeEvent");
            listeEvent.val(listeEvent.find('option:first').val()); //Retour à la première valeur

            listeEvent.on("change", function () {
                var eventChoisi = $(this).val();// Récupérez la valeur sélectionnée
                    
                // Mettez à jour les liens avec la nouvelle valeur
                $(".modEvent").attr("href", "unEvent.php?id=" + eventChoisi);
                $(".voteP").attr("href", "voteP.php?id=" + eventChoisi);
                $(".voteO").attr("href", "voteO.php?id=" + eventChoisi);
                $(".result").attr("href", "showVote.php?id=" + eventChoisi);
            });
        });
    </script>

</body>
</html>