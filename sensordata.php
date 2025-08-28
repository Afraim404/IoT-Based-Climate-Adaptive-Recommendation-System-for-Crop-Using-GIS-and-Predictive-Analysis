<?php
// Include DB connection
include("config.php");

// Check if POST data exists
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Security check (API key must match)
    $api_key_value = "1919";  // same as in ESP8266 code
    $api_key = $_POST["api_key"] ?? "";

    if ($api_key === $api_key_value) {
        // Get sensor values
        $temperature = isset($_POST["temperature"]) ? floatval($_POST["temperature"]) : null;
        $humidity    = isset($_POST["humidity"]) ? floatval($_POST["humidity"]) : null;

        if ($temperature !== null && $humidity !== null) {
            // Insert into database
            $sql = "INSERT INTO sensor_data (temperature, humidity) VALUES ('$temperature', '$humidity')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Data stored successfully: Temp=$temperature °C, Humidity=$humidity %";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Invalid sensor data!";
        }
    } else {
        echo "Invalid API Key!";
    }
} else {
    echo "No data posted!";
}

// Close connection
$conn->close();
?>
