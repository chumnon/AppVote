<?php 
session_start();
$_SESSION["connexion"] = false;

if (isset($_SESSION["vote"] )){
    $_SESSION["vote"] = false;
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="style/style.css" rel="stylesheet">
</head>
<body>
    <?php
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
            $sql = "SELECT 'user', 'mdp', 'id' FROM utilisateur";
            $result = $conn->query($sql);

            $user = $mdp = "";
            $userErreur = $mdpErreur = "";
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
                if ($erreur != true){
                    $codeTestUser = "SELECT * FROM utilisateur WHERE user LIKE '" . $user . "'";
                    $mdpCode = sha1($mdp);
                    $codeTestMdp = "SELECT * FROM utilisateur WHERE mdp LIKE '"  . $mdpCode . "' AND user LIKE '" . $user. "'";
                    $testUser = $conn->query($codeTestUser);
                    $testMdp = $conn->query($codeTestMdp);

                    if ($testUser->num_rows != 1){
                        $userErreur = "Cette utilisateur n'existe pas";
                        $erreur  = true;
                    }

                    if ($testMdp->num_rows != 1){
                        $mdpErreur = "Le mot de passe est incorrect";
                        $erreur  = true;
                    }
                }
            }

            if ($_SERVER['REQUEST_METHOD'] != "POST" || $erreur == true){
                ?>
                <div class="container-fluid" style="text-align:center">
                    <h1>Connection</h1>
                    <div class="row" style="text-align:left">
                        <div class="offset-md-5 ">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                Utilisateur : </br> <input type="text" name="user" maxLength="40" value="<?php echo $user;?>"><br>
                                <p style="color:red;"><?php echo $userErreur; ?></p>
                                Mots de passe : </br> <input type="password" name="mdp" maxLength="25"><br>
                                <p style="color:red;"><?php echo $mdpErreur; ?></p>
                                <input type="submit">
                            </form>
                        </div>
                    </div>
                </div>
            <?php
        } else {
            $_SESSION["connexion"] = true;
            $leUser =  "SELECT id FROM utilisateur WHERE user LIKE '" . $user . "'";
            $lID = $conn->query($leUser)->fetch_assoc();
            $_SESSION["user"] = $lID['id'];
            echo $_SESSION["user"];
            $url = "http://localhost/AppVote";
            header('Location:' . $url);
            Exit();
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