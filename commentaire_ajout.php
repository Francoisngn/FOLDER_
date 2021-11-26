<?php
session_start();
@$commentaire = $_POST["commentaire" ];

@$formation_id = $_POST["f"];
if($_SESSION["autoriser"]!=true){
    header("location:login.php");
    exit();
}
else if(isset($commentaire) && isset($formation_id))
{
    include("connexion_bdd.php");
    
    $sel=$pdo->prepare("select * from COMMENTAIRE where formation_id=?");
    $sel->execute(array($formation_id));
    $tab=$sel->fetchAll();
    $nb_com = count($tab) + 1;
    $upd1=$pdo->prepare("update FORMATIONS set nb_com=? where formation_id=?");
    $upd1->execute(array($nb_com, $formation_id));
    
    $upd=$pdo->prepare("insert into COMMENTAIRE(membre_id, contenu,formation_id) values(?,?,?)" );
    if($upd->execute(array($_SESSION["membre_id"],  htmlspecialchars($commentaire), $formation_id))) header("location:formation.php?f=".$formation_id);
}
?>