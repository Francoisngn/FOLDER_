<?php
   try{
      $pdo=new PDO("mysql:host=localhost;dbname=learnefrei","learnefreiuser","eisohx7b");
   }
   catch(PDOException $e){
      echo $e->getMessage();
   }
?>