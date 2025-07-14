<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "carbon_db";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch countries for dropdown
$countries = [];
$country_stmt = $conn->prepare("SELECT country_id, name FROM countries");
if ($country_stmt) {
    $country_stmt->execute();
    $result = $country_stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $countries[] = $row;
    }
    $country_stmt->close();
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $source = trim($_POST["source"]);
    $amount = filter_var($_POST["amount"], FILTER_VALIDATE_FLOAT);
    $date = $_POST["date"];
    $country_id = intval($_POST["country_id"]);

    if ($source && $amount !== false && $date && $country_id) {
        // Extract year from the date
        $year = date('Y', strtotime($date));

        // Find the corresponding `year_id` in the database
        $year_stmt = $conn->prepare("SELECT year_id FROM years WHERE year = ?");
        $year_stmt->bind_param("s", $year);
        $year_stmt->execute();
        $year_result = $year_stmt->get_result();
        $year_row = $year_result->fetch_assoc();
        $year_id = $year_row['year_id'] ?? null;
        $year_stmt->close();

        // If year doesn't exist, insert it
        if (!$year_id) {
            $insert_year_stmt = $conn->prepare("INSERT INTO years (year) VALUES (?)");
            $insert_year_stmt->bind_param("s", $year);
            $insert_year_stmt->execute();
            $year_id = $insert_year_stmt->insert_id; // Get the newly inserted `year_id`
            $insert_year_stmt->close();
        }

        // Insert emission data with extracted year
        $stmt = $conn->prepare("INSERT INTO emissions (source, amount, date, country_id, year_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsii", $source, $amount, $date, $country_id, $year_id);

        if ($stmt->execute()) {
            $message = "New record added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Invalid input data.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Carbon Emission Entry</title>
</head>
<body>
    <h1>Enter Carbon Emission Data</h1>
    <?php if ($message != ""): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
    
    <form method="post">
        <label>Source:</label><br>
        <input type="text" name="source" required><br><br>

        <label>Amount (kg CO2):</label><br>
        <input type="number" step="0.01" name="amount" required><br><br>

        <label>Date:</label><br>
        <input type="date" name="date" required><br><br>

        <label>Country:</label><br>
        <select name="country_id" required>
            <option value="">Select Country</option>
            <?php foreach ($countries as $row): ?>
                <option value="<?php echo htmlspecialchars($row['country_id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="submit" value="Save">
    </form>

    <br><a href="view.php">View All Records</a>
</body>
</html>
