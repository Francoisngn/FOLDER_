<?php
   session_start();
   @$cf_title = $_POST["ftitle"];
   @$cf_content = $_POST["fcontent"];
   @$view = $_GET["view"];
   @$tri = $_GET["tri"];
   if($_SESSION["autoriser"]!=true){
      header("location:login.php");
      exit();
   } else if (isset($cf_content) && isset($cf_title)) {
      if(empty($cf_title)) {
         $erreur = "Titre de la formation manquant";
      }
      else if(empty($cf_content)) {
         $erreur = "Contenu de la formation manquant";
      }
      else {
         $content = htmlspecialchars($cf_content);
         $title = htmlspecialchars($cf_title);
         include("connexion_bdd.php");
         $ins=$pdo->prepare("insert into FORMATIONS (matiere,contenu,membre_id) values(?,?,?)");
         if($ins->execute(array($title,$content, $_SESSION["membre_id"]))) header("location:espacepersonnel.php");
      }
   } else if($view == "formation") {
      include("connexion_bdd.php");
      if (isset($tri) && $tri == "pop") {
         $sel=$pdo->prepare(" select * from FORMATIONS ORDER BY nb_notes DESC limit 100");
      }
      else {
         $sel=$pdo->prepare("select * from FORMATIONS limit 100");
      }
      $sel->execute();
      $tab=$sel->fetchAll();
      if(count($tab)>0){
         foreach($tab as &$formation) {
            ?>
            <div class="formation-box">
               <a class="no-deco" href="formation.php?f=<?php echo $formation["formation_id"] ?>" target="_blank">
                  <h1>
                     <?php echo $formation["matiere"]?>
                  </h1>
                  <p class="md-transform">
                     <?php echo substr($formation["contenu"], 0, 300)."..."?>
                  </p>
                  <div class="formation-stats">
                     <div class="stat-views"><?php echo $formation["nb_notes"] ?> &#128065;</div>
                     <div class="stat-stars"><?php echo $formation["note"] ?> <span style="color:#ffd100;">&#9733;</span></div>
                     <div class="stat-com"><?php echo $formation["nb_com"] ?> &#9998;</div>
                  </div>
               </a>
            </div>
            <?php
         }
      }
      else {
         echo "Il n'y a pas de formation pour le moment.";
      }
   } else {
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
      <div class="sidebar" id="main-sidebar">
         <a class="active" id="view-button" href="#view">Formations disponibles</a>
         <a id="create-button" href="#create">Créer une formation</a>
      </div>
      <div class="content">
         [ <a href="disconnect.php">Se déconnecter</a> ]
         <div id="methode-tri">
            <button onclick="triDate()">Trier par date</button>
            <button onclick="triPop()">Trier par popularité</button>
         </div>
         <div id="formation-list"></div>
         <div id="create-form" style="display:hidden;">
            <form method="post" action="" id="createform">
               <label for="ftitle">Titre de la formation :</laberl>
               <input type="text" id="ftitle" name="ftitle"><br>
               <label for="fcontent">Contenu de la formation (format Markdown) :</label><br>
               <textarea id="fcontent" form="createform" name="fcontent"></textarea>
               <input type="submit">
            </form>
            <div id="markdown">

            </div>
         </div>
      </div>



   </body>
   <script>
      function triDate (){
         $.get("", {view: "formation", tri : "date" }).done(function(data) {
            $("#formation-list").html(data);
            $(".md-transform").each(function() {
               this.innerHTML = marked(this.innerHTML.trimStart());
            });
      });
      

      }

      function triPop (){
         $.get("", {view: "formation", tri : "pop" }).done(function(data) {
            $("#formation-list").html(data);
            $(".md-transform").each(function() {
               this.innerHTML = marked(this.innerHTML.trimStart());
            });
      });

      }
      
      
            $.get("", {view: "formation"}).done(function(data) {
            $("#formation-list").html(data);
      });
      $("#create-button").click(function() {
         $("#main-sidebar").children().each(function () {
            $(this).removeClass("active");
         });
         $(this).addClass("active");
         $("#create-form").show();
         $("#formation-list").hide();
      });
      $("#view-button").click(function() {
         $.get("", {view: "formation"}).done(function(data) {
            $("#formation-list").html(data);
            $(".md-transform").each(function() {
               this.innerHTML = marked(this.innerHTML.trimStart());
            });
         });
         $("#main-sidebar").children().each(function () {
            $(this).removeClass("active");
         });
         $(this).addClass("active");
         $("#create-form").hide();
         $("#formation-list").show();
      });
      $('#fcontent').bind('input propertychange', function() {
         document.getElementById('markdown').innerHTML = marked(this.value);
      });
      $("#view-button").click();
   </script>
</html>
<?php
   }
?>