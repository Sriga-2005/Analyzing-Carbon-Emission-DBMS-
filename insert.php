<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "carbon_db";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $source = $_POST['source'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    
    $sql = "INSERT INTO emissions (source, amount, date) VALUES ('$source', '$amount', '$date')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        echo "<br><a href='index.php'>Back to Form</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>