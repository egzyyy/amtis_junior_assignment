<?php
function calculateElectricity($voltage, $current, $rateSen)
{
    $powerWatt = $voltage * $current;
    $powerKW = $powerWatt / 1000;
    $rateRM = $rateSen / 100;

    $data = [];
    $dailyTotal = 0;

    for ($hour = 1; $hour <= 24; $hour++) {
        $energy = $powerKW * $hour;
        $total = $energy * $rateRM;
        $dailyTotal += $total;

        $data[] = [
            'hour' => $hour,
            'energy' => round($energy, 5),
            'total' => round($total, 2)
        ];
    }

    return [
        'powerKW' => round($powerKW, 5),
        'rateRM' => round($rateRM, 3),
        'dailyTotal' => round($dailyTotal, 2),
        'rows' => $data
    ];
}

$result = null;

if (isset($_POST['calculate'])) {
    $voltage = $_POST['voltage'];
    $current = $_POST['current'];
    $rate = $_POST['rate'];

    $result = calculateElectricity($voltage, $current, $rate);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Electricity Calculator</title>
    <link rel="stylesheet"
     href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="container mt-4">

<h3 class="mb-4">Electricity Consumption Calculator</h3>

<form method="POST" class="mb-4">
    <div class="form-group">
        <label>Voltage (V)</label>
        <input type="number" step="0.01" name="voltage" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Current (A)</label>
        <input type="number" step="0.01" name="current" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Current Rate (sen/kWh)</label>
        <input type="number" step="0.01" name="rate" class="form-control" required>
    </div>

    <button type="submit" name="calculate" class="btn btn-primary">
        Calculate
    </button>
</form>

<?php if ($result): ?>
<hr>

<p><strong>POWER :</strong> <?= $result['powerKW'] ?> kW</p>
<p><strong>RATE :</strong> <?= $result['rateRM'] ?> RM</p>

<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Hour</th>
            <th>Energy (kWh)</th>
            <th>Total (RM)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result['rows'] as $row): ?>
        <tr>
            <td><?= $row['hour'] ?></td>
            <td><?= $row['hour'] ?></td>
            <td><?= $row['energy'] ?></td>
            <td><?= $row['total'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h5>Total Charge Per Day: RM <?= $result['dailyTotal'] ?></h5>

<?php endif; ?>

</body>
</html>
