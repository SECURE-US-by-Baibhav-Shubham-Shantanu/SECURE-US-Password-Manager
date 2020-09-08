<?php
 $servername = "127.0.0.1";
 $username = "root";
 $password = "";

 try {
   $conn = new PDO("mysql:host=$servername;dbname=user",$username,$password);
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 } catch (PDOException $e) {
   echo "Connection failed: ".$e->getMessage();
 }
 ?>
