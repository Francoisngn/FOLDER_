<?php
session_start();
@$rating = $_POST["rating"];
@$formation_id = $_POST["f"];
if($_SESSION["autoriser"]!=true){
    header("location:login.php");
    exit();
}
else if(isset($rating) && isset($formation_id))
{
    include("connexion_bdd.php");

/* x=?  replace a data   */
    $sel=$pdo->prepare("select * from NOTATIONS where formation_id=?");
    $sel->execute(array($formation_id));
    $tab=$sel->fetchAll();
    $nb_notes = count($tab) + 1;
    $upd1=$pdo->prepare("update FORMATIONS set note_coeff=? where formation_id=?");
    $upd1->execute(array($nb_notes, $formation_id));


    $sel=$pdo->prepare("select * from NOTATIONS where membre_id=? and formation_id=? limit 1");
    $sel->execute(array($_SESSION["membre_id"], $formation_id));
    $tab=$sel->fetchAll();
    if(count($tab)>0) {
        $upd=$pdo->prepare("update NOTATIONS set note=? where notation_id=?");
        if($upd->execute(array($rating, $tab[0]["notation_id"]))) echo "ok";
    } else {
        $upd=$pdo->prepare("insert into NOTATIONS(membre_id,formation_id,note) values(?,?,?)");
        if($upd->execute(array($_SESSION["membre_id"], $formation_id, $rating))) echo "ok";
    }
}
?>