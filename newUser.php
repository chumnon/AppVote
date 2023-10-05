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
    <title>Nouvel utilisateur</title>

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
        $sql = "SELECT 'user', 'mdp', 'id' FROM utilisateur";
        $result = $conn->query($sql);
        $user = $mdp = $cmdp = "";
        $userErreur = $mdpErreur = $cmdpErreur = "";
        $erreur = false;
        
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if(empty($_POST['user'])){
                    $userErreur = "L'utilisateur ne peut pas être vide";
                    $erreur  = true;
                } else {
                    $user = trojan($_POST['user']);
                }

                if(empty($_POST['mdp'])){
                    $mdpErreur = "Le mot de passe ne peut pas être vide";
                    $erreur  = true;
                } else  {
                    $mdp = trojan($_POST['mdp']);
                }

                if(empty($_POST['cmdp'])){
                    $cmdpErreur = "La confirmation du mot de passe ne peut pas être vide";
                    $erreur  = true;
                } else  {
                    $cmdp = trojan($_POST['cmdp']);
                }

                if ($erreur != true){
                    if($mdp != $cmdp){
                        $cmdpErreur = "les mots de passe ne sont pas identique";
                        $erreur  = true;
                    }
                    $codeTestUser = "SELECT * FROM utilisateur WHERE user LIKE '" . $user . "'";
                    $mdpCode = sha1($mdp);
                    $testUser = $conn->query($codeTestUser);

                    if ($testUser->num_rows >= 1){
                        $userErreur = "Cette utilisateur exite déjà";
                        $erreur  = true;
                    }
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
                    <h1>Création utilisateur</h1>
                    <div class="row" style="text-align:left">
                        <div class="offset-md-5 ">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                Utilisateur : </br> <input type="text" name="user" maxLength="40" value="<?php echo $user;?>"><br>
                                <p style="color:red;"><?php echo $userErreur; ?></p>
                                Mots de passe : </br> <input type="password" name="mdp" maxLength="25"><br>
                                <p style="color:red;"><?php echo $mdpErreur; ?></p>
                                Confirmation : </br> <input type="password" name="cmdp" maxLength="25"><br>
                                <p style="color:red;"><?php echo $cmdpErreur; ?></p>
                                <input type="submit">
                            </form>
                        </div>
                    </div>
                </div>
            <?php
        } else {
            $envoye = "INSERT INTO utilisateur (user, mdp) VALUES ('" . $user . "', '" . $mdpCode . "');";
            if ($conn->query($envoye) === TRUE) {
                ?>
                    <div class="container-fluid" style="text-align:center">
                        <h1>Utilisateur enregistrer</h1>
                    </div>
                    <div class="container-fluid">
                        <div class= "row">
                            <div class="offset-md-4 offset-2 col-md-2 col-4">
                                <a href="index.php">Page principal</a>
                            </div>
        
                            <div class="col-md-2 col-4" >
                                <a href="newUser.php">Ajouter un autre utilisateur</a>
                            </div>
                        </div>
                    </div>
                <?php
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