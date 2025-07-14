<?php
require_once __DIR__ . "/config.php"; // Ensure correct file path

$id = $_GET['id'];

// Fetch existing data
$sql = "SELECT emissions.*, years.year AS emission_year FROM emissions 
        LEFT JOIN years ON emissions.year_id = years.year_id
        WHERE emissions.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $source = $_POST['source'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    // ✅ Extract year from the given date
    $year = date('Y', strtotime($date));

    // ✅ Check if year already exists in the database
    $year_stmt = $conn->prepare("SELECT year_id FROM years WHERE year = ?");
    $year_stmt->bind_param("s", $year);
    $year_stmt->execute();
    $year_result = $year_stmt->get_result();
    $year_row = $year_result->fetch_assoc();
    $year_id = $year_row['year_id'] ?? null;
    $year_stmt->close();

    // ✅ If year doesn't exist, insert it
    if (!$year_id) {
        $insert_year_stmt = $conn->prepare("INSERT INTO years (year) VALUES (?)");
        $insert_year_stmt->bind_param("s", $year);
        $insert_year_stmt->execute();
        $year_id = $insert_year_stmt->insert_id; // Get newly inserted `year_id`
        $insert_year_stmt->close();
    }

    // ✅ Update record with extracted year
    $update_sql = "UPDATE emissions SET source=?, amount=?, date=?, year_id=? WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sdsii", $source, $amount, $date, $year_id, $id);
    
    if ($update_stmt->execute()) {
        header("Location: view.php");
        exit();
    } else {
        echo "❌ Error updating record: " . $update_stmt->error;
    }

    $update_stmt->close();
}
$conn->close();
?>

<h2>Update Emission Record</h2>
<form method="POST">
    <label>Source:</label><br>
    <input type="text" name="source" value="<?php echo htmlspecialchars($row['source']); ?>" required><br><br>

    <label>Amount (kg CO2):</label><br>
    <input type="number" name="amount" value="<?php echo htmlspecialchars($row['amount']); ?>" required><br><br>

    <label>Date:</label><br>
    <input type="date" name="date" value="<?php echo htmlspecialchars($row['date']); ?>" required><br><br>

    <input type="submit" value="Update">
</form>
<br>
<a href="view.php">Back to View</a>
