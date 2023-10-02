<?php 
session_start();
if (isset($_SESSION["connexion"])){
} else {
    $_SESSION["connexion"] = false;
};
$_SESSION['vote'] = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évènements</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/scss/bootstrap.scss" rel="stylesheet">
    <link href="style/style.css" rel="stylesheet">
</head>
<body>
    <?php
        if($_SESSION["connexion"] != true){
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

            if (isset($_GET['id'])){
                $id = $_GET['id'];
            } else if (isset ($_POST['id'])){
                $id = $_POST['id'];
            } else {
                    $idErreur = "Erreur chargement de la page";
                    $erreur = true;
            }

            if (isset($_GET['valeur'])){
                $valeur = $_GET['valeur'];
            } else if (isset ($_POST['valeur'])){
                $valeur = $_POST['valeur'];
            } else {
                    $valeur = -1;
            }

            $conn->query('SET NAMES utf8');
            $monEvent = "SELECT nom, id FROM evenement WHERE id LIKE '" . $id . "'";
            $lEvent = $conn->query($monEvent)->fetch_assoc();

            if($valeur === -1 || $_SERVER['REQUEST_METHOD'] != "POST"){
                ?>
                    <div class="container-fluid menu ">
                        <div class="row">
                            <h1><?php echo $lEvent['nom']?></h1>
                        </div>
                        <div class="row space">
                            <canvas id="monCanvas" width="300" height="100">" ></canvas>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                            <div class="row rangeRow">
                                <input type="range" class="form-range" name="range" id="range">
                                <input type="hidden" class="valeur" name="valeur" value="-1">
                            </div>
                            <div class="row">
                                <input type="hidden" name="vote" class="bouton">
                                <input type="hidden" name="id" value="<?php echo $id;?>">
                        </form>
                        </div>
                    <?php
            } else {
                $envoye = "INSERT INTO vote (avis, participant, evenementID) VALUES ('" . $valeur . "', 1, '" . $id . "');";
            if ($conn->query($envoye) === TRUE) {
                ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <input type="hidden" class="valeur" name="valeur" value="-1">
                        <input type="hidden" name="id" value="<?php echo $id;?>">
                    </form>
                    <div class="container-fluid menu ">
                        <div class="row">
                            <h1>Merci!</h1>
                        </div>
                    </div>
                <?php
                $page = $_SERVER['PHP_SELF'];
                $sec = "2";
                header("Refresh: $sec; url=$page?id=" . $id);
            } else {
                ?>
                <h1><?php echo "Error: " . $envoye . "<br>" . $conn->error; ?></h1>
                <a class="optionBar" href="index.php">Page principal</a>
                <?php
            }
                
            }
        $conn->close();
        }
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            var range = $("#range");
            var canvas = document.getElementById('monCanvas');
            var context = canvas.getContext('2d');
            const dpr = 30;
            canvas.width = 300 * dpr;
            canvas.height = 100 * dpr;
            
            context.scale(dpr, dpr);

            var rouge = 155;
            var vert = 155;
            var bleu= 155;
            var mid = 1;
            var Ax = 125; var Ay = 65;
            var Bx = 150; var By = 65;
            var Cx = 175; var Cy = 65;
            
            context.fillStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
            context.beginPath();
            context.arc(150, 50, 40, 0, 2 * Math.PI);
            context.fill();

            context.fillStyle="white";
            context.beginPath();
            context.arc(150, 50, 35, 0, 2 * Math.PI);
            context.fill();

            context.fillStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
            context.beginPath();
            context.arc(135, 40, 4, 0, 2 * Math.PI);
            context.fill();
            context.fillStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
            context.beginPath();
            context.arc(165, 40, 4, 0, 2 * Math.PI);
            context.fill();

            context.beginPath();
            context.moveTo(Ax, Ay);
            context.quadraticCurveTo(Bx, By, Cx, Cy);
            context.strokeStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
            context.lineWidth = 4;
            context.stroke();

            range.on("input", function () {
                $(".valeur").attr("value", range.val());
                $(".bouton").attr("type", "submit");
                if(range.val()<=50){
                    mid = 1;
                } else {
                    mid = 0;
                }

                bleu = 0;
                Ay = 72 - (range.val()*14/100);
                By = 40 + (range.val()*50/100);
                Cy = 72 - (range.val()*14/100);
                if(mid == 1){
                    vert = 0 + 2 * (range.val() * 255 / 100);
                    rouge = 255;
                    context.fillStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
                    context.beginPath();
                    context.arc(150, 50, 40, 0, 2 * Math.PI);
                    context.fill();

                    context.fillStyle="white";
                    context.beginPath();
                    context.arc(150, 50, 35, 0, 2 * Math.PI);
                    context.fill();

                    context.fillStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
                    context.beginPath();
                    context.arc(135, 40, 4, 0, 2 * Math.PI);
                    context.fill();
                    context.fillStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
                    context.beginPath();
                    context.arc(165, 40, 4, 0, 2 * Math.PI);
                    context.fill();

                    context.beginPath();
                    context.moveTo(Ax, Ay);
                    context.quadraticCurveTo(Bx, By, Cx, Cy);
                    context.strokeStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
                    context.lineWidth = 4;
                    context.stroke();
                } else {
                    vert = 255;
                    rouge = 255 - ((range.val()-50) * 255 / 50);
                    context.fillStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
                    context.beginPath();
                    context.arc(150, 50, 40, 0, 2 * Math.PI);
                    context.fill();

                    context.fillStyle="white";
                    context.beginPath();
                    context.arc(150, 50, 35, 0, 2 * Math.PI);
                    context.fill();

                    context.fillStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
                    context.beginPath();
                    context.arc(135, 40, 4, 0, 2 * Math.PI);
                    context.fill();
                    context.fillStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
                    context.beginPath();
                    context.arc(165, 40, 4, 0, 2 * Math.PI);
                    context.fill();

                    context.beginPath();
                    context.moveTo(Ax, Ay);
                    context.quadraticCurveTo(Bx, By, Cx, Cy);
                    context.strokeStyle = 'rgb(' + rouge + ',' + vert + ',' + bleu + ')';
                    context.lineWidth = 4;
                    context.stroke();
                }
                
               
            });
        });
        
        
    </script>

</body>
</html>