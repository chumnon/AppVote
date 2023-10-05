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
    <title>Modifier compte</title>

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
        $sql = "SELECT 'user', 'mdp', 'id' FROM utilisateur";
        $result = $conn->query($sql);
        $monUser = "SELECT user, id FROM utilisateur WHERE id LIKE '" . $user . "'";
        $leUser = $conn->query($monUser)->fetch_assoc();

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
                        if ($user != $leUser['user']){
                        $userErreur = "Cette utilisateur exite déjà";
                        $erreur  = true;
                        }
                    }
                }
            }

            if ($_SERVER['REQUEST_METHOD'] != "POST" || $erreur == true){
                ?>
                <div class="container-fluid banniere banniereMod">
                    <div class="row navBar navBarMod">
                            <h1 class="appLogo appLogoMod">M-NAV</h1>
                    </div>
                </div>

                <div class="container-fluid menuMod" style="text-align:center">
                    <h1 class="titreMod">Modifier utilisateur</h1>
                    <div class="row" style="text-align:left">
                        <div class="boxModUser">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                <p class="catUserMod">Utilisateur : </p> <input type="text" name="user" class="inputUserMod" maxLength="40" value="<?php echo $leUser["user"];?>"><br>
                                <p style="color:red;"><?php echo $userErreur; ?></p>
                                <p class="catUserMod">Mots de passe : </p> <input type="password" name="mdp" class="inputUserMod" maxLength="25"><br>
                                <p style="color:red;"><?php echo $mdpErreur; ?></p>
                                <p class="catUserMod">Confirmation : </p> <input type="password" name="cmdp" class="inputUserMod" maxLength="25"><br>
                                <p style="color:red;"><?php echo $cmdpErreur; ?></p>
                                <div class="boxBtn">
                                    <input type="submit" value="modifer" class="btnModUser">
                                    <a href="profil.php?id=<?php echo $leUser['id']?>" class="btnAnnulerMod">annuler</a> 
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
        } else {
            $update = "UPDATE utilisateur SET user = '" . $user . "', mdp = '" . $mdpCode ."' WHERE id = " . $leUser['id'];
            if ($conn->query($update) === TRUE) {
                ?>
                <div class="container-fluid menuSup">
                    <div class="row blockSup">
                        <h1 class="titreCon">Utilisateur modifier</h1>
                        <a href="profil.php?id=<?php echo $leUser['id']?>" class="linkCon">profil</a> 
                    </div>
                </div>
                <?php
            } else {
                ?>
                <h1><?php echo "Error: " . $update . "<br>" . $conn->error; ?></h1>
                <a href="index.php">Page principal</a>
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