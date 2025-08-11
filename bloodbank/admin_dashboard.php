<?php
session_start();
// Replace with your own admin check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}

// Manually trigger model update if button was clicked
if (isset($_POST['update_predictions'])) {
    $output = shell_exec("python scripts/predict_blood_demand.py 2>&1");
    header("Location: admin_dashboard.php?updated=1");
    exit();
}

// Connect to DB and fetch predictions
$conn = new mysqli('localhost', 'root', '', 'bloodbank');
$result = $conn->query("SELECT blood_type, predicted_units FROM blood_predictions ORDER BY blood_type");
$predictions = [];
while ($row = $result->fetch_assoc()) {
    $predictions[] = (int)$row['predicted_units'];
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Blood Demand Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { margin: 20px; }
        .container { max-width: 900px; margin: auto; }
    </style>
</head>
<body>
<div class="container" id="graph-container">
    <h1 class="mb-4">Blood Demand Prediction Dashboard</h1>
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">Predictions updated successfully!</div>
    <?php endif; ?>

    <form method="post" class="mb-4">
        <button type="submit" name="update_predictions" class="btn btn-primary">Update Predictions</button>
    </form>

    <canvas id="predictionsChart" id="bloodPredictionChart" height="300"></canvas>
</div>
<button onclick="printGraph()" class="btn btn-primary mb-3">
    Print Graph
</button>
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #graph-container, #graph-container * {
        visibility: visible;
    }
    #graph-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
}
</style>
<div id="graph-container">
   
    <canvas id="bloodPredictionChart"></canvas>
</div>


<script>
const bloodTypes = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];
const predictedDemands = <?php echo json_encode($predictions); ?>;

const ctx = document.getElementById('predictionsChart').getContext('2d');
const predictionsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: bloodTypes,
        datasets: [{
            label: 'Predicted Units Needed Next Month',
            data: predictedDemands,
            backgroundColor: 'rgba(220, 53, 69, 0.7)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: true, text: 'Predicted Blood Demand by Blood Type' }
        },
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Units' } },
            x: { title: { display: true, text: 'Blood Type' } }
        }
    }
});
function printGraph() {
    window.print();
}

</script>
</body>
</html>
