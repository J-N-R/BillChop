<?php

   //
   // joinBill.php
   //
   // Description:
   //    PHP Script that will let someone join a bill using a bill code
   //    Validates that their bill code is in the database, and adds 1
   //    to the person count for that bill.
   //
   //
   // Made By: rivejona@kean.edu
   //

   include "dbconfig.php";


// Helper function to print error message in JSON
   function print_error($message) {
      DIE("{\"Error\":\"$message\"}");
   }


// If bid isn't set, print error
   if(!isset($_GET['bid']))
      print_error("BID not detected.");

// Go to database and test if the given bill exists
   $conn = mysqli_connect($host, $user, $password, $database) or print_error("Can't connect to the database.");

   $sql = "SELECT bid FROM Bills WHERE bid=" . $_GET['bid'];
   
   $results = mysqli_query($conn, $sql) or print_error("Can't run SQL query. Query = $sql");

// If the bill exists, update user count, give user all the items to display on page
   if($results) {

   // Update Count of Users
      $sql = "UPDATE Bills SET users = users + 1 WHERE bid=" . $_GET['bid'];

      mysqli_query($conn, $sql) or print_error("Can't run second SQL query. Query = $sql");

   // Retrieve list of items, serve to user through JSON
      $sql = "SELECT * FROM Items WHERE bid=" . $_GET['bid'];
      
      $results = mysqli_query($conn, $sql) or print_error("Can't run third SQL query. Query = $sql");

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

// If the bill doesn't exist, print error
   else {
      print_error("No bill was found with that code.");
   }
?>
