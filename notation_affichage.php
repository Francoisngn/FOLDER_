<?php

session_start();
@$formation_id = $_POST["f"];
if (isset($formation_id)) {
    include("connexion_bdd.php");
    
    $sel=$pdo->prepare("select * from NOTATIONS where formation_id=?");
    $sel->execute(array($formation_id));
    $tab=$sel->fetchAll();
    $notation = 0;
    $total_note = 0;
    foreach($tab as &$note) {
        $notation = $notation + $note["note"];
        $total_note = $total_note + 1;
    }
    $notation = $notation/$total_note;
    $self_note = 0;
    if ($_SESSION["autoriser"] == true) {
        $sel=$pdo->prepare("select * from NOTATIONS where membre_id=? and formation_id=? limit 1");
        $sel->execute(array($_SESSION["membre_id"], $formation_id));
        $tab=$sel->fetchAll();
        if (count($tab)>0) $self_note = $tab[0]["note"];
    }

    $upd1=$pdo->prepare("update FORMATIONS set note=? where formation_id=?");
    $upd1->execute(array($notation, $formation_id));


    echo $notation.",".$total_note.",".$self_note;
}
else echo "error";

?>