<?php

   //
   // splitEven.php
   //
   // Description:
   //    PHP Script that will calculate thePHP Script that will calculate 
   //    the price owed by dividing by the number of users which is
   //    given or taken from the database using a given bill code, 
   //    and outputs the data in JSON
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
//if number of users is specified use that number otherwise grab from database
if(isset($_GET['users'])) 
   {
   $sql = "SELECT SUM(price)/". $_GET['users'] . " as Total FROM Items WHERE bid=" . $_GET['bid'];
   }
else
   {
   $sql = "SELECT SUM(i.price)/b.users as Total FROM Items i, Bills b WHERE b.bid=" . $_GET['bid'];
   }
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