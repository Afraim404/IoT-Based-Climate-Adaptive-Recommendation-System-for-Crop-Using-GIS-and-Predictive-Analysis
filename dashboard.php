<?php
include "config.php";

// Fetch vegetables for dropdown
$vegQuery = "SELECT * FROM vegetables";
$vegResult = $conn->query($vegQuery);
$selectedVeg = isset($_GET['vegetable']) ? intval($_GET['vegetable']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Smart Farming Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Header -->
  <header>
    <h1>🌱 Smart Farming Dashboard</h1>
    <p>Monitor and adjust your farm environment in real-time</p>
  </header>

  <!-- Main Content -->
  <main>
    <section class="card">
      <h2>Select Vegetable</h2>
      <form method="GET" action="">
        <select name="vegetable" onchange="this.form.submit()">
          <option value="">-- Choose a vegetable --</option>
          <?php while($row = $vegResult->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>" <?= $row['id']==$selectedVeg?'selected':'' ?>>
              <?= $row['name'] ?>
            </option>
          <?php endwhile; ?>
        </select>
      </form>
    </section>

    <!-- Dynamic Section -->
    <div id="live-data">
      <p>Loading sensor data...</p>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    <p>© <?= date("Y") ?> Smart Farming | Built with ❤️ for Farmers</p>
  </footer>

  <!-- Auto Refresh Script -->
  <script>
    function loadData() {
      const vegId = "<?= $selectedVeg ?>";
      if (vegId > 0) {
        fetch("fetch_data.php?vegetable=" + vegId)
          .then(response => response.text())
          .then(data => {
            document.getElementById("live-data").innerHTML = data;
          });
      } else {
        document.getElementById("live-data").innerHTML = "<p>Please select a vegetable to see live data.</p>";
      }
    }

    // Load data immediately and every 5 seconds
    loadData();
    setInterval(loadData, 5000);
  </script>
</body>
</html>
