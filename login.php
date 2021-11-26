<?php
   session_start();
   @$email=$_POST["email"];
   @$password=$_POST["pass"];
   @$valider=$_POST["valider"];
   $erreur="";
   if(isset($valider)){
      include("connexion_bdd.php");
      $sel=$pdo->prepare("select * from MEMBRE where email=? limit 1");
      $sel->execute(array($email));
      $tab=$sel->fetchAll();
      if(count($tab)>0){
        if (password_verify($password, $tab[0]["password"])) {
            $_SESSION["prenomNom"]=ucfirst(strtolower($tab[0]["prenom"])).
            " ".strtoupper($tab[0]["nom"]);
            $_SESSION["autoriser"]=true;
            $_SESSION["membre_id"] = $tab[0]["membre_id"];

            header("location:espacepersonnel.php");
        }
        else {
            $erreur ="Mot de passe incorrect";
        }
      }
      else {
          $erreur ="Ce compte n'existe pas";
      }
   }
?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <style>
         *{
            font-family:arial;
         }
         
         body{
            margin:20px;
         }
         
         input{
            border:solid 1px #2222AA;
            margin-bottom:10px;
            padding:16px;
            outline:none;
            border-radius:6px;
         }
         .erreur{
            color:#CC0000;
            margin-bottom:10px;
         }
         a{
            font-size:12pt;
            color:#EE6600;
            text-decoration:none;
            font-weight:normal;
         }
         a:hover{
            text-decoration:underline;
         }

      </style>
   </head>
   <body onLoad="document.fo.email.focus()">
      <h1>Authentification [ <a href="inscription.php">Créer un compte</a> ]</h1>
      <div class="erreur"><?php echo $erreur ?></div>
      <form name="fo" method="post" action="">
         <input type="text" name="email" placeholder="email" /><br />
         <input type="password" name="pass" placeholder="Mot de passe" /><br />
         <input type="submit" name="valider" value="S'authentifier" />
      </form>
   </body>
</html>