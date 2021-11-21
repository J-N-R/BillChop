<?php

   //
   // getTotals.php
   //
   // Description:
   //    PHP Script that will retrieve the totals for each user from
   //    the database using a given bill code, and outputs the data in JSON
   //
   //
   // Made By: sampsoth@kean.edu
   //
   
   include "dbconfig.php";


// Helper function to print error message in JSON
   function print_error($message) {
      DIE("{\"Error\":\"$message\"}");
   }
   

// If bid isn't set, print error
   if(!isset($_GET['bid']))
      print_error("BID not detected.");

// Go to the database and retrieve all the items for a certain bill
   $conn = mysqli_connect($host, $user, $password, $database) or print_error("Can't connect to the database.");

// Group by user, and sum their items to find their totals. Items without owners (NULL) will be unpaid.
   $sql = "SELECT owner, SUM(price) as total FROM Items WHERE bid=" . $_GET['bid'] . " GROUP BY owner";

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
    
?>