<?php
include "dbconfig.php"

# Check if fields are set
if (!isset($_GET['bid']) or !isset($_GET['id']) or !isset($_GET['owner']))
{
	die("One or more fields are not set.");
}

# Get fields
$bid = $_GET['bid'];
$id = $_GET['id'];
$owner = $_GET['owner'];

# Set up connection
$conn mysqli_connect($host, $user, $password, $database) or die("Can't connect to the database.");

# Update item owner
$sql = "UPDATE Items SET owner=$owner WHERE bid=$bid AND id=$id";
mysqli_query($conn, $sql) or die("SQL query failed. Query = $sql");

# If update succeeded, then retrive updated item
$sql = "SELECT id, bid, owner FROM Items WHERE id=$id AND bid=$bid AND owner=$owner";
$results = mysqli_query($conn, $sql) or die("SQL query failed. Query = $sql");

if ($results)
{
	# If found, should only fetch one row since id is unique
	$row = mysqli_fetch_assoc($results);

	# Generate JSON of results and terminate
	die(json_encode($row));
}
else
{
	die("Item not found.");
}
?>