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
    <title>Resultat</title>

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
                    $idErreur = "Erreur chargement de la page";
                    $erreur = true;
            }

            $conn->query('SET NAMES utf8');
            $monEvent = "SELECT nom, avisParticipant, avisOrganisateur, id FROM evenement WHERE id LIKE '" . $id . "'";
            $listeVote = "SELECT id, avis, participant, evenementID FROM vote WHERE evenementID IN (SELECT id FROM evenement WHERE id LIKE '" . $id . "')";
            $lesVotes = $conn->query($listeVote);
            $lEvent = $conn->query($monEvent)->fetch_assoc();

            $totalP = 0;
            $compteurP = 0;
            $totalO = 0;
            $compteurO = 0;

            while($total = $lesVotes->fetch_assoc()){
                if ($total['participant'] === "1"){
                    $totalP += (int)$total['avis'];
                    $compteurP += 1;
                } else if ($total['participant'] === "0"){
                    $totalO += (int)$total['avis'];
                    $compteurO += 1;
                }
            }

            if ($compteurP == 0){
                $moyenneP = 0;
            } else {
                $moyenneP = $totalP / $compteurP;
                $moyenneP = number_format((float)$moyenneP, 2, '.', '');
            }

            if ($compteurO == 0){
                $moyenneO = 0;
            } else {
                $moyenneO = $totalO / $compteurO;
                $moyenneO = number_format((float)$moyenneO, 2, '.', '');
            }

            $insertAvis = "UPDATE evenement SET avisParticipant = '" . $moyenneP . "', avisOrganisateur = '" . $moyenneO . "' WHERE id = " . $lEvent['id'];
            if ($conn->query($insertAvis) === TRUE) {
            } else {
                ?>
                <h1><?php echo "Error: " . $update . "<br>" . $conn->error; ?></h1>
                <?php
            }

            ?>
            <div class="container-fluid menu">
                <div class="row retour">
                    <div class="col-3 offset-md-9">
                        <a href="index.php">Page principal</a>
                    </div>
                </div>
                <div class="row lEvent">
                    <h1><?php echo $lEvent['nom']?></h1>
                </div>
                <div class="row">
                    <div class="col-6 offset-md-2 col-md-3">
                        <p>Vote des participants</p>
                    </div>
                    <div class="col-6 offset-md-2 col-md-3">
                        <p>Vote des organisateurs</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 offset-md-2 col-md-3">
                        <p><?php echo $lEvent['avisParticipant']; ?>%</p>
                    </div>
                    <div class="col-6 offset-md-2 col-md-3">
                        <p><?php echo $lEvent['avisOrganisateur']; ?>%</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 offset-md-2 col-md-3">
                        <p><?php echo $compteurP; ?> votes</p>
                    </div>
                    <div class="col-6 offset-md-2 col-md-3">
                        <p><?php echo $compteurO; ?> votes</p>
                    </div>
                </div>
            </div>
        <?php       
        $conn->close();
        }
    ?>

</body>
</html>