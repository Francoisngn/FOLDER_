<?php

session_start();
@$formation_id = $_POST["f"];
if (isset($formation_id)) {
    include("connexion_bdd.php");

    $sel=$pdo->prepare("select * from COMMENTAIRE where formation_id=?");
    $sel->execute(array($formation_id));
    $tab=$sel->fetchAll();

    
    foreach($tab as &$commentaire) {
        $sel=$pdo->prepare("select * from MEMBRE where membre_id=?");
    $sel->execute(array($commentaire["membre_id"]));

    $tab2=$sel->fetchAll();
        ?>
        <p class="commentaire-item">
        <span class="commentaire-auteur"><?php echo $tab2[0]["prenom"]." ".$tab2[0]["nom"] ?>  </span>
        <?php echo $commentaire["contenu"] ?> </p>
        <?php
    }
}
else echo "Aucun commentaire";

?>