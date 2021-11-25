<?php

  include "dbconfig.php";

  $conn = mysqli_connect($host, $user, $password, $database) or die("{\"Error\":\"Can't connect to database\"}");

  $sql = "SELECT * FROM Items WHERE bid=" . $_GET["bid"];

  $results = mysqli_query($conn, $sql) or die("{\"Error\":\"Can't run SQL command\"}");

  $data = array();

  while($row = mysqli_fetch_assoc($results))
     $data[] = $row;

  echo json_encode($data);
?>
