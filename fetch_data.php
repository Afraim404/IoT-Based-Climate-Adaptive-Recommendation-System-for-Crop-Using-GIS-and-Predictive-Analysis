<?php
include "config.php";

$selectedVeg = isset($_GET['vegetable']) ? intval($_GET['vegetable']) : 0;

if ($selectedVeg > 0) {
    // Get latest sensor data
    $sensorQuery = "SELECT * FROM sensor_data ORDER BY id DESC LIMIT 1";
    $sensorData = $conn->query($sensorQuery)->fetch_assoc();

    // Get vegetable requirements
    $vegQuery = "SELECT * FROM vegetables WHERE id=$selectedVeg";
    $veg = $conn->query($vegQuery)->fetch_assoc();

    if ($sensorData && $veg) {
        $tempDiff = $veg['required_temp'] - floatval($sensorData['temperature']);
        $humDiff  = $veg['required_humidity'] - floatval($sensorData['humidity']);

        echo '<section class="card">';
        echo '<h2>Latest Sensor Data</h2>';
        echo '<p><strong>Temperature:</strong> '.$sensorData['temperature'].' °C</p>';
        echo '<p><strong>Humidity:</strong> '.$sensorData['humidity'].' %</p>';
        echo '<small>Reading Time: '.$sensorData['reading_time'].'</small>';
        echo '</section>';

        echo '<section class="card highlight">';
        echo '<h2>Feedback</h2><ul>';
        echo '<li>Temperature: Current '.$sensorData['temperature'].'°C → ';
        echo $tempDiff > 0 ? "Increase by ".round($tempDiff,1)."°C" :
             ($tempDiff < 0 ? "Decrease by ".abs(round($tempDiff,1))."°C" : "Perfect!");
        echo '</li>';

        echo '<li>Humidity: Current '.$sensorData['humidity'].'% → ';
        echo $humDiff > 0 ? "Increase by ".round($humDiff,1)."%" :
             ($humDiff < 0 ? "Decrease by ".abs(round($humDiff,1))."%" : "Perfect!");
        echo '</li></ul></section>';

        echo '<section class="card">';
        echo '<h2>Growing Tips</h2>';
        echo '<p>'.$veg['tips'].'</p>';
        echo '</section>';
    }
} else {
    echo "<p>No vegetable selected.</p>";
}
