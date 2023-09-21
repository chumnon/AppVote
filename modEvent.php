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
    <title>modifier évènement</title>

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

        if (isset($_GET['id'])){
            $id = $_GET['id'];
        } else if (isset ($_POST['id'])){
            $id = $_POST['id'];
        } else {
                $idErreur = "Erreur chargement de la page";
                $erreur = true;
        } 

        $user = $_SESSION['user'];

        $conn->query('SET NAMES utf8');
        $monEvent = "SELECT nom, date, lieu, departement, description, id FROM evenement WHERE id LIKE '" . $id . "'";
        $listeUserNonMod = "SELECT user , id FROM utilisateur WHERE id NOT IN (SELECT user FROM gestion WHERE evenement IN (SELECT id FROM evenement WHERE id LIKE '" . $id . "'))";
        $listeUserMod = "SELECT user , id FROM utilisateur WHERE id IN (SELECT user FROM gestion WHERE evenement IN (SELECT id FROM evenement WHERE id LIKE '" . $id . "')) AND user NOT LIKE '" . $user . "'";
        $lEvent = $conn->query($monEvent)->fetch_assoc();
        $nonMod = $conn->query($listeUserNonMod);
        $mod = $conn->query($listeUserMod);

        $nom = $lEvent['nom']; 
        $date =  $lEvent['date']; 
        $lieu =  $lEvent['lieu']; 
        $departement =  $lEvent['departement']; 
        $description =  $lEvent['description'];
        
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

            ?>
            <div class="container-fluid" style="text-align:center">
            <div class="row retour">
                    <div class="col-3 offset-md-9">
                        <a href="index.php">Page principal</a>
                    </div>
                </div>
            </div>
            <div class="container-fluid" style="text-align:center">
            <h1>Modification évènement</h1>
                <div class="row">
            <?php
            echo $_SERVER['REQUEST_METHOD'];
            
                ?>
                <div class="offset-md-2 col-md-4">
                    <div class="row" style="text-align:left">
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
                                    <input type="hidden" name="id" value="<?php echo $id;?>">
                        </form>
                    </div>
                </div>
                <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST" || $erreur != true){
                $_SERVER['REQUEST_METHOD'] = "GET";
            }
        }
        ?>
            <div class="col-md-4">
                <h2>Gestion des gestionnaires</h2>
                    <div class="row" style="text-align:left">
                        <h3>Ajouter les droits</h3>
                        <select class="choixUser" id="listenonMod">
                        <?php
                        if ($nonMod->num_rows > 0){
                            while($row = $nonMod->fetch_assoc()){
                            ?>
                                <option value="<?php echo $row['id']?>"><?php echo $row['user']?>
                                <?php
                            }
                        } else {
                            ?>
                            <option value="-1">Aucun user</option>
                            <?php
                        }
                        ?>
                        </select>
                        <button id="addMod">ajouter</button>
                        </div>
                        <div class="row" style="text-align:left">
                            <h3>Gestionnaire</h3>
                            <?php
                            if ($mod->num_rows > 0){
                                while($row = $mod->fetch_assoc()){
                                ?>
                                <div class="container">
                                    <div class="row" >
                                        <div class="col-11">
                                            <a><?php echo $row['user']?></a>
                                        </div>
                                        <div class="col-1">
                                            <a class="supMod" id="supMod <?php echo $row['id']?>">x</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                            ?>
                                <option value="-1">Aucun gestionnaire</option>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
            </div>
        <?php
        

        function trojan($data){
        $data = trim($data); //Enleve les caractères invisibles
        $data = addslashes($data); //Mets des backslashs devant les ' et les  "
        $data = htmlspecialchars($data); // Remplace les caractères spéciaux par leurs symboles comme ­< devient &lt;
            
        return $data;
    }

    $conn->close();
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            var listenonMod = $("#listenonMod");
            var boutonAdd = $("#addMod");
            var boutonAdd = $("#supMod");

            boutonAdd.on("click", function () {
                var eventChoisi = $(this).val();// Récupérez la valeur sélectionnée
                    
                // Mettez à jour les liens avec la nouvelle valeur
                $(".modEvent").attr("href", "modEvent.php?id=" + eventChoisi);
                $(".voteP").attr("href", "voteP.php?id=" + eventChoisi);
                $(".voteO").attr("href", "voteO.php?id=" + eventChoisi);
                $(".result").attr("href", "showVote.php?id=" + eventChoisi);
            });
        });
    </script>

</body>
</html>