<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "carbon_db";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "DELETE FROM emissions WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: view.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>