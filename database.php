<?php
    function setUpDBConnection(){
        $hostName = "localhost";
        $userName = "root";
        $password = "";
        $database = "ecommerceweb";
        $connection = mysqli_connect($hostName, $userName, $password, $database);
        return $connection;
   }
?>