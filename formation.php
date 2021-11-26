<?php
 session_start();
 @$formation_id = $_GET["f"];
 $title = "";
 $content = "";
 $error = "";
 $nb_note = 0;

 if($_SESSION["autoriser"]!=true){
    header("location:login.php");
    exit();
 } else if (isset($formation_id)) {
    include("connexion_bdd.php");
    $sel=$pdo->prepare("select * from FORMATIONS WHERE formation_id=? limit 1");
    $sel->execute(array($formation_id));
    $tab=$sel->fetchAll();
    if(count($tab)>0){
        $nb_note = 1;
        if($tab[0]["nb_notes"] === NULL) $nb_note = 1;
        else $nb_note = $tab[0]["nb_notes"]+1;

        $upd=$pdo->prepare("update FORMATIONS set nb_notes=? where formation_id=?" );
        $upd->execute(array($nb_note, $formation_id));

        $title = $tab[0]["matiere"];
        $content = $tab[0]["contenu"];
    }
    else {
        $error= "Impossible d'accéder à la formation demandée.";
    }
}
    else {
        $error = "Aucune formation spécifiée.";   
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
         
      </style>
      <link href="css/main.css" rel="stylesheet">
      <script src="js/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
   </head>
   <body>
      <div class="content">
      [ <a href="disconnect.php">Se déconnecter</a> ]
      <p class="error"><?php echo $error ?></p>
      <h1 class="ftitle"><?php echo $title ?></h1>
      <p  ><?php echo $nb_note ?>  vues</p>
        <form id="rating-form-display">
            <span class="rating-star-display">
                <input type="radio" name="rating" value="5"><span class="star"></span>
                <input type="radio" name="rating" value="4"><span class="star"></span>
                <input type="radio" name="rating" value="3"><span class="star"></span>
                <input type="radio" name="rating" value="2"><span class="star"></span>
                <input type="radio" name="rating" value="1"><span class="star"></span>
            </span>
        </form>
        <form id="rating-form">
            <span class="rating-star">
       <!--           radio is a type to check only one    -->  
                <input id="star-check" type="radio" name="rating" value="5"><span class="star"></span>
                <input type="radio" name="rating" value="4"><span class="star"></span>
                <input type="radio" name="rating" value="3"><span class="star"></span>
                <input type="radio" name="rating" value="2"><span class="star"></span>
                <input type="radio" name="rating" value="1"><span class="star"></span>
            </span>
        </form>
        <p id="self-rating"></p>
        <p id="total-ratings"></p>
         <div id="markdown">
         <?php echo $content ?>
         </div>
         <form method="post" id="envoi-commentaire" action="commentaire_ajout.php">
            <input type="text"name="commentaire">
            <input type="hidden" name="f" value=<?php echo $formation_id ?> >
            <input type="submit">
         </form>
         <div id ="commentaires">
         </div>
      </div>
      </div>
   </body>
   <script>

   /*   on met dans la partie script functions  */

       function getURLParams()
		{
			var url = document.location.href.split("?");
			
			if(url.length > 1)
			{
				// Params found un URL !
				var get = new Object;
				var params = url[1].split("&");

				for(var i in params)
				{
					var tmp = params[i].split("=");
					get[tmp[0]] = unescape(tmp[1].replace("+", " "));
				}
				
				// Return Object (data are accessible in array too) : get["paramname"] = get.paramname
				return get;
			}
			
			// No params found in URL !
			return false;
		}


        function updateNote() {
            $.post( "notation_affichage.php", { f: getURLParams()["f"]}).done(function( data ) {
                var noteData = data.split(",");
                $("#rating-form").width(parseFloat(noteData[0])*36.3 + 1 + "px");
                if(parseInt(noteData[2]) !== 0) $("#self-rating").text("Vous avez donné " + noteData[2] + " étoile(s).");
                $("#total-ratings").text(noteData[1] + " évaluations")
            });
        }
        updateNote();
        function postNoteDisplay(){
            $.post( "notation.php", { f: getURLParams()["f"], rating: parseInt($('#rating-form-display .rating-star-display [name="rating"]:checked').val())})
            .done(function( data ) {
                $('#rating-form .rating-star [name="rating"]').eq(5-parseInt($('#rating-form-display .rating-star [name="rating"]:checked').val())).attr("checked", true);
                updateNote();
            });
        }
        function postNoteFront(){
            $.post( "notation.php", { f: getURLParams()["f"], rating: parseInt($('#rating-form .rating-star [name="rating"]:checked').val())})
            .done(function( data ) {
                $('#rating-form-display .rating-star [name="rating"]').eq(5-parseInt($('#rating-form .rating-star [name="rating"]:checked').val())).attr("checked", true);
                updateNote();
            });
        }
        $('#rating-form-display').on('change','[name="rating"]', postNoteDisplay);
        $('#rating-form').on('change','[name="rating"]', postNoteFront);
      document.getElementById('markdown').innerHTML = marked(document.getElementById('markdown').innerHTML.trimStart());

      function Printcommentaire() {

          /*  f is identifiant for formation_ id) */
            $.post ( "commentaire_affichage.php" , { f: getURLParams ( ) ['f']  }).done(function(data) {
                /* $.post is a request  to get data */
                
                $("#commentaires").html(data);

            });

      }
      Printcommentaire();
   </script>
{ f: getURLParams ( ) ['f']  }).done(function(data) {
</html>