<?php
require_once __DIR__ . "/config.php"; // Ensures correct file path

// Verify connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch data for filters
$countries = $conn->query("SELECT country_id, name FROM countries");

// Fetch available years dynamically from the emissions table
$years = $conn->query("SELECT DISTINCT YEAR(date) AS year FROM emissions ORDER BY year DESC");

// Apply filters if set
$country_filter = isset($_GET['country_id']) ? intval($_GET['country_id']) : '';
$year_filter = isset($_GET['year']) ? intval($_GET['year']) : '';

// Retrieve emissions data
$query = "SELECT emissions.*, countries.name AS country FROM emissions 
          LEFT JOIN countries ON emissions.country_id = countries.country_id";

if ($country_filter || $year_filter) {
    $query .= " WHERE ";
    if ($country_filter) {
        $query .= "emissions.country_id = $country_filter ";
    }
    if ($year_filter) {
        $query .= ($country_filter ? "AND " : "") . "YEAR(emissions.date) = $year_filter ";
    }
}

$data_rows = $conn->query($query);

// Fetch total & average emissions
$total_query = "SELECT SUM(amount) AS total, AVG(amount) AS average FROM emissions";
$total_result = $conn->query($total_query);
$total_data = $total_result->fetch_assoc();
$total_emissions = $total_data['total'] ?? 0;
$average_emissions = $total_data['average'] ?? 0;

// Prepare data for Chart.js visualization
$dates = [];
$amounts = [];
$sources = [];

while ($row = $data_rows->fetch_assoc()) {
    $dates[] = $row['date'];
    $amounts[] = $row['amount'];
    $sources[$row['source']] = ($sources[$row['source']] ?? 0) + $row['amount'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carbon Emission Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Carbon Emission Analysis</h1>
    
    <form method="GET">
        <label>Filter by Country:</label>
        <select name="country_id">
            <option value="">All</option>
            <?php while ($row = $countries->fetch_assoc()): ?>
                <option value="<?php echo $row['country_id']; ?>" <?php echo ($country_filter == $row['country_id']) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($row['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Filter by Year:</label>
        <select name="year">
            <option value="">All</option>
            <?php while ($row = $years->fetch_assoc()): ?>
                <option value="<?php echo $row['year']; ?>" <?php echo ($year_filter == $row['year']) ? "selected" : ""; ?>>
                    <?php echo $row['year']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Apply Filter</button>
    </form>

    <h2>Total Emissions: <?php echo $total_emissions; ?> kg CO2</h2>
    <h3>Average Emissions: <?php echo round($average_emissions, 2); ?> kg CO2</h3>

    <canvas id="trendChart" width="150" height="55"></canvas>
    <canvas id="pieChart" width="250" height="55"></canvas>

    <h2>All Emission Records</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Source</th>
            <th>Amount (kg CO2)</th>
            <th>Date</th>
            <th>Country</th>
            <th>Year</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($data_rows as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['source']); ?></td>
            <td><?php echo htmlspecialchars($row['amount']); ?></td>
            <td><?php echo htmlspecialchars($row['date']); ?></td>
            <td><?php echo htmlspecialchars($row['country']); ?></td>
            <td><?php echo htmlspecialchars(date('Y', strtotime($row['date']))); ?></td>

            <td>
                <a href="update.php?id=<?php echo urlencode($row['id']); ?>">Edit</a> |
                <a href="delete.php?id=<?php echo urlencode($row['id']); ?>" onclick="return confirm('Delete this record?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br><a href="index.php">Add New Entry</a>

    <script>
    const ctx1 = document.getElementById('trendChart').getContext('2d');
    const trendChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Carbon Emissions Over Time',
                data: <?php echo json_encode($amounts); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });

    // ➡️ Paste THIS pie chart fix here ⬇️
    const ctx2 = document.getElementById('pieChart').getContext('2d');
    document.getElementById('pieChart').width = 700; // Force new width
    document.getElementById('pieChart').height = 700; // Force new height

    const pieChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode(array_keys($sources)); ?>,
            datasets: [{
                label: 'Emission Sources',
                data: <?php echo json_encode(array_values($sources)); ?>,
                backgroundColor: ['red', 'blue', 'green', 'yellow', 'purple', 'orange', 'indigo', 'brown', 'pink']
            }]
        },
        options: {
            responsive: false, // Disable automatic resizing
            maintainAspectRatio: false // Allow custom width/height
        }
    });
</script>

</body>
</html>
