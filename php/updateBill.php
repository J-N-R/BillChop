<?php

   //
   // updateBill.php
   //
   // Description:
   //    PHP Script that will update the database everytime a user
   //    selects an item. 
   //
   //
   // Made By: borgesj@kean.edu
   //

   include "dbconfig.php";


// Helper function to print error message in JSON
   function print_error($message) {
      DIE("{\"Error\":\"$message\"}");
   }


// If bid, id, or owner isn't set, print error
   if (!isset($_GET['bid']) or !isset($_GET['id']) or !isset($_GET['owner']))
      print_error("One or more fields are not set.");

// Get fields
   $bid = $_GET['bid'];
   $id = $_GET['id'];
   $owner = $_GET['owner'];

// Go to database, and update the item with the given bid and id with their new owner.
   $conn = mysqli_connect($host, $user, $password, $database) or print_error("Can't connect to the database.");

   $sql = "UPDATE Items SET owner='$owner' WHERE bid=$bid AND id=$id";
   mysqli_query($conn, $sql) or print_error("SQL query failed. Query = $sql");
   
   DIE("{\"Success\":\"Item has been updated. Query = $sql\"}");

?>