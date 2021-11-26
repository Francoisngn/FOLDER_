<?php
   try{
      $pdo=new PDO("mysql:host=localhost;dbname=sil_mysql","sil_mysql","eisohx7b");
   }
   catch(PDOException $e){
      echo $e->getMessage();
   }
?>