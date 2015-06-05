<html>
<head><title>Inscription</title></head>
<style>
.error {color: #FF0000;}
</style>
<body>

<?php
include "connect.php";
$vConn = fConnect();
// define variables and set to empty values
$loginErr = $mdpErr = "";
$login = $nom = $prenom = $mdp = "";
$succes1=$succes2=0;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["login"])) {
     $loginErr = "Champs obligatoire";
   }
   else {
     $login=$_POST["login"];
     if (strlen($login)>30) {
       $loginErr = "Moins de 30 caracteres, s'il vous plait";
     }
     else {
	if(strlen($login)<7){
	   $loginErr = "Au moins 7 caracteres, s'il vous plait";
	}
	else {
	    if (!ctype_alnum($login)){
		$loginErr = "Que des lettres et chiffres, s'il vous plait";
	    }
	    else{
		$vSql ="SELECT login FROM TUSER WHERE login= '$login';";
		$vQuery=pg_query($vConn, $vSql);
		$vResult = pg_fetch_array($vQuery);
		if($vResult['login']!=NULL){
			$loginErr = "Le login existe deja";
	    	}
		else{$succes1=1;}
	    }
        }
    }
   }
    if (!empty($_POST["nom"]))$nom=$_POST["nom"];
    if (!empty($_POST["prenom"]))$prenom=$_POST["prenom"];

    if (empty($_POST["mdp"])) {
     $mdpErr = "Champs obligatoire";
   } else {
     $mdp=$_POST["mdp"];
     if (strlen($mdp)<8) {
       $mdpErr = "Au moins 8 caracteres, s'il vous plait";
     }
     else {
	if (preg_match("/\\s/", $mdp)) {
		$mdpErr = "Pas d'epace, s'il vous plait";
	}
	else{$succes2=1;}

     }
   }

}
	if(($succes1==1) and ($succes2==1)){
	$vSql="INSERT INTO TUSER (login, firstname, lastname, aPassword, droit) VALUES ('$login','$prenom','$nom','$mdp', 'lecteur');";
	$vQuery=pg_query($vConn,$vSql);
	}
	if($succes1==1 && $succes2==1){Header("Location: acceuil.php?login=".$login);}
?>

<h2>Creer un nouveau compte</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="">
   Login(7~30 caracteres): <input type="text" name="login" value="<?php echo $login;?>">
   <span class="error">* <?php echo $loginErr;?></span>
   <br><br>
   Nom: <input type="text" name="nom" value="<?php echo $nom;?>">
   <br><br>
   Prenom: <input type="text" name="prenom" value="<?php echo $prenom;?>">
   <br><br>
   Mot de passe: <input type="password" name="mdp" value="<?php echo $mdp;?>">
   <span class="error">* <?php echo $mdpErr;?></span>
   <br><br>
   <input type="submit" name="submit" value="Submit">
</form>
</body>
</html>
