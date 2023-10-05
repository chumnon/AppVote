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
    <title>modifier évènement</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="style/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        $user = $_SESSION['user'];

        $conn->query('SET NAMES utf8');
        $monEvent = "SELECT nom, date, lieu, departement, description, id FROM evenement WHERE id LIKE '" . $id . "'";
        $listeUserNonMod = "SELECT user , id FROM utilisateur WHERE id NOT IN (SELECT user FROM gestion WHERE evenement IN (SELECT id FROM evenement WHERE id LIKE '" . $id . "'))";
        $listeUserMod = "SELECT user , id FROM utilisateur WHERE id IN (SELECT user FROM gestion WHERE evenement IN (SELECT id FROM evenement WHERE id LIKE '" . $id . "')) AND user NOT LIKE '" . $user . "'";
        $lEvent = $conn->query($monEvent)->fetch_assoc();

        $nom = $lEvent['nom']; 
        $date =  $lEvent['date']; 
        $lieu =  $lEvent['lieu']; 
        $departement =  $lEvent['departement']; 
        $description =  $lEvent['description'];
        
        $nomErreur = $dateErreur = $lieuErreur = $departementErreur = $descriptionErreur = "";
        $erreur = false;
        
            if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['modEvent'])){
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

            if(isset($_POST['ajouter'])){
                $giveDroit = "INSERT INTO gestion (user , evenement) VALUES ('" . $_POST['idMod'] . "', '" . $lEvent['id'] . "');";
                if ($conn->query($giveDroit) != TRUE) {
                    echo '<script type="text/javascript">';
                    echo ' alert("Droit non accordé")'; 
                    echo '</script>';
                }
            }

            if(isset($_POST['sup'])){
                $enleverDroit = "DELETE FROM gestion WHERE user LIKE '" . $_POST['idMod'] . "' AND evenement LIKE '" . $lEvent['id'] . "'";
                if ($conn->query($enleverDroit) != TRUE) {
                    echo '<script type="text/javascript">';
                    echo ' alert("Droit non accordé")'; 
                    echo '</script>';
                }
            }

            
            $nonMod = $conn->query($listeUserNonMod);
            $mod = $conn->query($listeUserMod);

            ?>

            <div class="container-fluid banniere banniereMod">
                <div class="row navBar navBarMod">
                        <h1 class="appLogoAlt appLogoAltMod">M-NAV</h1>
                        <h1 class="navBarTitre navBarTitreMod">Modification</h1>
                        <a class="navBarOption navBarOptionMod" href="index.php">Page principal</a>
                </div>
            </div>

            <div class="container-fluid menuMod" style="text-align:center">
                <div class="row">
                <?php
                ?>
                <div class="offset-md-2 col-md-4">
                    <div class="row" style="text-align:left">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                    <p class='catEventMod'>Nom :</p> <input type="text" name="nom" maxLength="64" value="<?php echo $nom;?> "><br>
                                    <p style="color:red;"><?php echo $nomErreur; ?></p>
                                    <p class='catEventMod'>Date :</p> <input type="date" name="date" value="<?php echo $date;?>"><br>
                                    <p style="color:red;"><?php echo $dateErreur; ?></p>
                                    <p class='catEventMod'>Lieu :</p> <input type="text" name="lieu" maxLength="128" value="<?php echo $lieu;?>"><br>
                                    <p style="color:red;"><?php echo $lieuErreur; ?></p>
                                    <p class='catEventMod'>Departement : </p> <input type="text" name="departement" maxLength="64" value="<?php echo $departement;?>"><br>
                                    <p style="color:red;"><?php echo $departementErreur; ?></p>
                                    <p class='catEventMod'>Description : </p> <input type="text" name="description" maxLength="255" value="<?php echo $description;?>"><br>
                                    <p style="color:red;"><?php echo $descriptionErreur; ?></p>
                                    <input type="submit" name="modEvent" class='btnModEvent' value='modifier'>
                                    <input type="hidden" name="id" value="<?php echo $id;?>">
                        </form>
                        <?php
                        if($_SERVER['REQUEST_METHOD'] == "POST" && $erreur != true && isset($_POST['modEvent'])){
                            $update = "UPDATE evenement SET nom = '" . $nom . "', date = '" . $date ."', lieu = '" . $lieu ."', departement = '" . $departement ."', description = '" . $description ."' WHERE id = " . $lEvent['id'];
                            if ($conn->query($update) === TRUE) {
                                echo '<script type="text/javascript">';
                                echo ' alert("Evenement modifier")'; 
                                echo '</script>';
                            } else {
                                ?>
                                <h1><?php echo "Error: " . $update . "<br>" . $conn->error; ?></h1>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
        }
        ?>
            <div class="col-md-4">
                <h2 class='titreMod'>Gestion des gestionnaires</h2>
                    <div class="row boxAddMod" style="text-align:left">
                        <h3 class='sousTitreMod'>Ajouter les droits</h3>
                        <select class="choixUserMod" id="listenonMod">
                        <?php
                        if ($nonMod->num_rows > 0){
                            while($row = $nonMod->fetch_assoc()){
                            if (isset($option1)){
                                } else {
                                    $option1 = $row['id'];
                                }
                            ?>
                                <option value="<?php echo $row['id']?>" class='unUserMod'><?php echo $row['user']?></option>
                            <?php
                            }
                        } else {
                            ?>
                            <option value="-1">Aucun utilisateur</option>
                            <?php
                        }
                        ?>
                        </select>
                        <?php
                        if ($nonMod->num_rows > 0){
                            ?>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                <input type="submit" name="ajouter" value="ajouter" class='btnAddMod'>
                                <input type="hidden" name="id" value="<?php echo $id;?>">
                                <input class="modChoisi" type="hidden" name="idMod" value="<?php echo $option1;?>">
                             </form>
                             <?php
                        } else {
                        }
                        ?>
                        </div>
                        <div class="row" style="text-align:left">
                            <h3 class='sousTitreMod'>Gestionnaire</h3>
                            <?php
                            if ($mod->num_rows > 0){
                                while($row = $mod->fetch_assoc()){
                                ?>
                                <div class="container">
                                    <div class="row boxUserAvecMod" >
                                        <div class="col-11 ">
                                            <a class='unUserAvecMod'><?php echo $row['user']?></a>
                                        </div>
                                        <div class="col-1">
                                            <?php
                                            if ($row['id'] == $_SESSION['user']){
                                            } else {
                                            ?>
                                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                                <input type="submit" name="sup" value="x" class='btnSupMod'>
                                                <input type="hidden" name="id" value="<?php echo $id;?>">
                                                <input class="modChoisi" type="hidden" name="idMod" value="<?php echo $row['id']?>">
                                            </form>
                                            <?php
                                            }
                                            ?>
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

    <script>
        $(document).ready(function () {
            var listeNonMod = $("#listenonMod");
            listeNonMod.val(listeEvent.find('option:first').val()); //Retour à la première valeur

            listeNonMod.on("change", function () {
                var modChoisi = $(this).val();// Récupérez la valeur sélectionnée
                    
                // Mettez à jour les liens avec la nouvelle valeur
                $(".modEvent").attr("value", modChoisi);
            });
        });
    </script>

</body>
</html>