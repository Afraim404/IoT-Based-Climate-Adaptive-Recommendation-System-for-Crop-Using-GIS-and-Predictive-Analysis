<?php
include("config.php");

if (isset($_GET['veg_id'])) {
    $veg_id = intval($_GET['veg_id']);

    // Latest sensor data
    $sensor = $conn->query("SELECT * FROM sensor_data ORDER BY id DESC LIMIT 1")->fetch_assoc();

    // Vegetable requirements
    $veg = $conn->query("SELECT * FROM vegetables WHERE id=$veg_id")->fetch_assoc();

    if ($sensor && $veg) {
        $temp_diff = $veg['req_temp'] - $sensor['temperature'];
        $hum_diff  = $veg['req_humidity'] - $sensor['humidity'];

        echo "<h2>".$veg['name']."</h2>";
        echo "<p><b>Current Temperature:</b> ".$sensor['temperature']." °C</p>";
        echo "<p><b>Required Temperature:</b> ".$veg['req_temp']." °C</p>";

        if ($temp_diff > 0) {
            echo "<p>🔥 Need to INCREASE temperature by <b>".abs($temp_diff)." °C</b></p>";
        } elseif ($temp_diff < 0) {
            echo "<p>❄️ Need to DECREASE temperature by <b>".abs($temp_diff)." °C</b></p>";
        } else {
            echo "<p>✅ Temperature is perfect</p>";
        }

        echo "<br><p><b>Current Humidity:</b> ".$sensor['humidity']." %</p>";
        echo "<p><b>Required Humidity:</b> ".$veg['req_humidity']." %</p>";

        if ($hum_diff > 0) {
            echo "<p>💧 Need to INCREASE humidity by <b>".abs($hum_diff)." %</b></p>";
        } elseif ($hum_diff < 0) {
            echo "<p>🌬️ Need to DECREASE humidity by <b>".abs($hum_diff)." %</b></p>";
        } else {
            echo "<p>✅ Humidity is perfect</p>";
        }

        echo "<br><h3>Tips:</h3>";
        echo "<p>".$veg['tips']."</p>";
    } else {
        echo "<p>⚠️ No data available!</p>";
    }
} else {
    echo "<p>⚠️ No vegetable selected.</p>";
}
?>
