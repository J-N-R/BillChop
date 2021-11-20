<?php

   //
   // getTotals.php
   //
   // Description:(revisit)
   //    PHP Script that when called will return a JSON for giving
   //    the total a user owes

   include "dbconfig.php";

   // Helper function to print error message in JSON
   function print_error($message) {
    DIE("{\"Error\":\"$message\"}");
 }

 // If bid isn't set, print error
 if(!isset($_GET['bid']))
 print_error("BID not detected.");

 // Connect to database
 $conn = mysqli_connect($host, $user, $password, $database) or print_error("Can't connect to the database.");

 // Get cost of purchases for each individual
 $sql = "SELECT Owner, SUM(price) as Total FROM Items WHERE bid=" . $_GET['bid'] . "GROUP BY owner";

 $results = mysqli_query($conn, $sql) or print_error("Can't run SQL query. Query = $sql");
 // If there are valid items, print out to webpage
      if(mysqli_num_rows($results) > 0) {
         
         $data = array();
         while($row = mysqli_fetch_assoc($results)) {
            $data[] = $row;
         }

         DIE(json_encode($data));
      }

      else {
         print_error("No bill items found.");
      }
   } 
?>