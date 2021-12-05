<?php

   //
   // imageReader.php
   //
   // Description:
   //    PHP Script that will retrieve the bill image from the user
   //    and create a new bill in the database.
   //
   //    Uses the free OCR.Space API. Which goes down very often.
   //
   // Made By: rivejona@kean.edu
   //
   
   include "dbconfig.php";

// Helper function to print error message in JSON
   function print_error($message) {
      DIE("{\"Error\":\"$message\"}");
   }


// Disable http failure warnings
   error_reporting(0);
   
// Set timeout for API to 4 seconds
   ini_set('default_socket_timeout', 12);
   
// Validate Image
   $target_dir = "uploads/";
   $FileType = strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));

   $target_file = $target_dir . "receipt." . $FileType ;
   
// If image is too big
   if ($_FILES["file"]["size"] > 1000000) 
       print_error("Image is too big.");
   
// If image isn't the right file type
   if($FileType != "pdf" && $FileType != "png" && $FileType != "jpg" && $FileType != "jpeg")
       print_error("Wrong file type! Given: $FileType");

// File is good, move to uploads folder
   if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file))
       print_error("Error uploading file");

   chmod($target_file, 0777);

// Initialize important variables for the API
   $apikey = "90dd9f55d188957";

   $imgurl = "http://thejonathanrivera.com/BillChop/php/$target_file";

   $url  = "https://api.ocr.space/parse/imageurl?apikey=$apikey&url=$imgurl&isTable=true&detectOrientation=true&scale=true";
   // $fullUrl = $simpleUrl . "&scale=true&OCREngine=2";

// Run the API. Return error if something goes wrong
   $receiptJSON = file_get_contents($url, 0, stream_context_create(["http"=>["timeout"=>10]])) or print_error("API Error.");

// Decode the result, and further decode it to only get the large text block
   $receiptArray = json_decode($receiptJSON, true);
   $receiptText = $receiptArray["ParsedResults"][0]["ParsedText"];

// Split the large text block into lines
   $receiptLines = explode("\r\n", $receiptText);

// Create a result array of items that we want to fill
   $items = array();
   $bid = rand(0, 20);
   $total = 0;

   while($bid == 3 || $bid == 2)
      $bid = rand(0, 20);


// ITEM FINDING ALGORITHM
//    Go through every line to see if there is a price,
//    formatted like this (number).(number)
//    If there is, assume its an item

   for($i = 0; $i < count($receiptLines); $i++) {
     $words = explode("\t", str_replace(" ", "\t", $receiptLines[$i]));
  
     $itemName = "";
     $itemCost = "";
  
  // Test if current line contains an item. If it does, set the price
     foreach($words as $word) {
         $temp = explode(".", str_replace(" ", ".", $word));

         if(count($temp) > 1) {
            $numericCount = 0;

            foreach($temp as $token)
               if(is_numeric($token)) {
                  $numericCount++;
                  $itemCost .= $token . ".";
               }

            if($numericCount < 2)
               $itemCost = "";
          }
      }
  
   // If a price wasn't set, we couldn't find an item.
   // If we found an item...
      if($itemCost != "") {

      // Try to set the item name to all the words that aren't numbers
         foreach($words as $word) {
            if(!is_numeric($word)) {
            
            // If we find the word "total" or "change" delete this item
               if(strpos(strtolower($word), "total") !== false || strpos(strtolower($word), "change") !== false)
                   break 2;
               
               $itemName .= " " . $word;
            }
         }
    
         $temp = explode(".", $itemCost);
         $itemCost = $temp[0] . "." . $temp[1];
         $itemCost = floatval($itemCost);

         $total += $itemCost;
         
         if(str_replace(" ", "", $itemName) == "")
             $itemName = "Unknown";

         array_push($items, array("name" => $itemName, "price" => $itemCost));
      }
   
   }

   if(count($items) == 0)
       print_error("Couldn't read the items.");

   $successMessage = "{\"Success\":\"New bill $bid created. Details: ";

// After all the items have been read, create a new bill in the database
   $conn = mysqli_connect($host, $user, $password, $database);

// Delete previously existing bill, if any
   $sql = "DELETE FROM Users_Colors WHERE bid=$bid";
   mysqli_query($conn, $sql) or print_error("Can't run SQL query. Query = $sql");
   
// Add bill to bills table
   $sql = "INSERT INTO Bills VALUES ($bid, 1, $total)";

   mysqli_query($conn, $sql) or print_error("Can't run SQL query. Query = $sql");

// Insert all the items into the items table
   $sql = "INSERT INTO Items (bid, name, price) VALUES ($bid, ?, ?)";
   
   $statement = mysqli_prepare($conn, $sql);
   
   mysqli_stmt_bind_param($statement, 'sd', $name, $price);
   
   foreach($items as $item) {
       $name = $item["name"];
       $price = $item["price"];
       mysqli_stmt_execute($statement) or print_error("Can't run prepared statement.");
       $successMessage .= "$name $$price has been inserted.";
   }

   DIE($successMessage . "\", \"bid\":\"$bid\"}");

?>
