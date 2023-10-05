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
    <title>Nouvel évènement</title>

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
            $db = "appvote";

            //Connexion
            $conn = new mysqli($servername, $username,$password,$db);

            //Verrification
            if ($conn ->connect_error){
                die("Erreur de connexion: " . $conn->connect_error);
            }

        $conn->query('SET NAMES utf8');
        $sql = "SELECT 'nom', 'date', 'lieu', 'departement', 'description' , 'avisParticipant' , 'avisOrganisateur' , 'id' FROM evenement";
        $result = $conn->query($sql);

        $user = $_SESSION['user'];
        $monUser = "SELECT user, id FROM utilisateur WHERE id LIKE '" . $user . "'";
        $leUser = $conn->query($monUser)->fetch_assoc();

        $nom = $date = $lieu = $departement = $description = "";
        $nomErreur = $dateErreur = $lieuErreur = $departementErreur = $descriptionErreur = "";
        $erreur = false;
        
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if(empty($_POST['nom'])){
                    $nomErreur = "Le nom ne peut pas être vide";
                    $erreur  = true;
                } else {
                    $nom = trojan($_POST['nom']);
                }

                if(empty($_POST['date'])){
                    $dateErreur = "La date ne peut pas être vide";
                    $erreur  = true;
                } else {
                    $date = $_POST['date'];
                }

                if(empty($_POST['lieu'])){
                    $lieuErreur = "Le lieu ne peut pas être vide";
                    $erreur  = true;
                } else  {
                    $lieu = trojan($_POST['lieu']);
                }

                if(empty($_POST['departement'])){
                    $departementErreur = "Le departement ne peut pas être vide";
                    $erreur  = true;
                } else  {
                    $departement = trojan($_POST['departement']);
                }

                if(empty($_POST['description'])){
                    $descriptionErreur = "La description ne peut pas être vide";
                    $erreur  = true;
                } else  {
                    $description = trojan($_POST['description']);
                }
            }

            if ($_SERVER['REQUEST_METHOD'] != "POST" || $erreur == true){
                ?>
                <div class="container-fluid" style="text-align:center">
                    <div class="row retour">
                        <div class="col-3 offset-md-9">
                            <a href="index.php">Page principal</a>
                        </div>
                    </div>
                    <h1>Création Évènement</h1>
                    <div class="row" style="text-align:left">
                        <div class="offset-md-5 ">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                Nom : </br> <input type="text" name="nom" maxLength="64" value="<?php echo $nom;?>"><br>
                                <p style="color:red;"><?php echo $nomErreur; ?></p>
                                Date : </br> <input type="date" name="date" value="<?php echo $date;?>"><br>
                                <p style="color:red;"><?php echo $dateErreur; ?></p>
                                Lieu : </br> <input type="text" name="lieu" maxLength="128" value="<?php echo $lieu;?>"><br>
                                <p style="color:red;"><?php echo $lieuErreur; ?></p>
                                Departement : </br> <input type="text" name="departement" maxLength="64" value="<?php echo $departement;?>"><br>
                                <p style="color:red;"><?php echo $departementErreur; ?></p>
                                Description : </br> <input type="text" name="description" maxLength="255" value="<?php echo $description;?>"><br>
                                <p style="color:red;"><?php echo $descriptionErreur; ?></p>
                                <input type="submit">
                            </form>
                        </div>
                    </div>
                </div>
            <?php
        } else {
            $envoye = "INSERT INTO evenement (nom, date, lieu, departement, description, avisParticipant, avisOrganisateur) VALUES ('" . $nom . "', '" . $date . "', '" . $lieu . "', '" . $departement . "', '" . $description . "', '0', '0');";
            if ($conn->query($envoye) === TRUE) {
                $getDroit = "SELECT nom , id FROM evenement ";
                $listeEvent = $conn->query($getDroit);
                while ($lesEvent = $listeEvent->fetch_assoc()){
                    $lastID = $lesEvent['id'];
                }
                    
                $giveDroit = "INSERT INTO gestion (user , evenement) VALUES ('" . $leUser['id'] . "', '" . $lastID . "');";
                if ($conn->query($giveDroit) === TRUE) {
                ?>
                    <div class="container-fluid" style="text-align:center">
                        <h1>Évènement enregistrer</h1>
                    </div>
                    <div class="container-fluid">
                        <div class= "row">
                            <div class="offset-md-4 offset-2 col-md-2 col-4">
                                <a href="index.php">Page principal</a>
                            </div>
        
                            <div class="col-md-2 col-4" >
                                <a href="newEvent.php">Ajouter un autre évènement</a>
                            </div>
                        </div>
                    </div>
                <?php
                } else {
                    ?>
                    <h1><?php echo "Error: " . $giveDroit . "<br>" . $conn->error; ?></h1>
                    <a class="optionBar" href="index.php">Page principal</a>
                    <?php
                }
            } else {
                ?>
                <h1><?php echo "Error: " . $envoye . "<br>" . $conn->error; ?></h1>
                <a class="optionBar" href="index.php">Page principal</a>
                <?php
            }
        }
    }

    function trojan($data){
        $data = trim($data); //Enleve les caractères invisibles
        $data = addslashes($data); //Mets des backslashs devant les ' et les  "
        $data = htmlspecialchars($data); // Remplace les caractères spéciaux par leurs symboles comme ­< devient &lt;
            
        return $data;
    }

    $conn->close();
    ?>
</body>
</html>