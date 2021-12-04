<?php

   //
   // setColor.php
   //
   // Description:
   //    PHP Script that assigns a color to a webuser
   //
   // Made By: rivejona@kean.edu
   //

   include "dbconfig.php";


// Helper function to print error message in JSON
   function print_error($message) {
      DIE("{\"Error\":\"$message\"}");
   }


// If owner or bid isn't set, tell user
   if(!isset($_GET['owner']) || !isset($_GET['bid']))
      print_error("Owner / Bid is missing.");

   $conn = mysqli_connect($host, $user, $password, $database) or print_error("Can't connect to the database.");

// Go to database and make sure that this user doesn't already have a color
   $sql = "SELECT * FROM Users_Colors WHERE bid=" . $_GET['bid'] . " AND owner='" . $_GET['owner'] . "'";

   $results = mysqli_query($conn, $sql) or print_error("Can't run SQL query. Query = $sql");
   
   if(mysqli_num_rows($results) > 0)
       DIE("{\"Success\":\"User already has a color.\"}");

// Go to database and see if there are any unused colors for this bill
   $sql = "SELECT * FROM Colors WHERE cid NOT IN (SELECT cid FROM Users_Colors WHERE bid=" . $_GET["bid"] . ")";
   
   $results = mysqli_query($conn, $sql) or print_error("Can't run second SQL query. Query = $sql");

// If there are available colors, give user one
   if(mysqli_num_rows($results) > 0) {

      $row = mysqli_fetch_assoc($results);
      
      $sql = "INSERT INTO Users_Colors VALUES ('" . $_GET['owner'] . "', " . $row['cid'] . ", " . $_GET['bid'] . ")";
      
      mysqli_query($conn, $sql) or print_error("Can't run third SQL query. Query = $sql");

      DIE("{\"Success\":\"User has successfully been given a color. Query = $sql\"}");
   }

// If there are no more available colors
   else {
      print_error("There are no more available colors.");
   }
?>
