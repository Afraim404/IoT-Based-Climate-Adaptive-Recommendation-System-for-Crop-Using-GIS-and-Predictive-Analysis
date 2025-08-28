#include <ESP8266WiFi.h>
#include <DHT.h>

// ---------- WiFi Credentials ----------
const char* ssid     = "Blank1919";      // 🔹 Replace with your WiFi SSID
const char* password = "sunmoon1919";  // 🔹 Replace with your WiFi Password

// ---------- Server Settings ----------
const char* host = "192.168.0.107";  // 🔹 Replace with your PC’s local IP (check with ipconfig)
const int port = 3000;               // 🔹 Using :3000 since your Apache runs on that port

// ---------- DHT Sensor Settings ----------
#define DHTPIN D4       // GPIO2 (D4 pin on NodeMCU)
#define DHTTYPE DHT11   // Or use DHT22 if your sensor is DHT22

DHT dht(DHTPIN, DHTTYPE);

void setup() {
  Serial.begin(115200);
  delay(10);

  dht.begin();

  Serial.println();
  Serial.print("Connecting to WiFi: ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected!");
  Serial.print("ESP IP Address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  delay(5000); // send data every 5 seconds

  float temperature = dht.readTemperature();  // °C
  float humidity    = dht.readHumidity();     // %

  // Check if readings are valid
  if (isnan(temperature) || isnan(humidity)) {
    Serial.println("Failed to read from DHT sensor!");
    return;
  }

  // Connect to server
  WiFiClient client;
  if (!client.connect(host, port)) {
    Serial.println("Connection to server failed!");
    return;
  }

  // Create POST data (must match sensordata.php)
  String postData = "api_key=1919&temperature=" + String(temperature) + "&humidity=" + String(humidity);

  // Debug: show what’s being sent
  Serial.println("----- Sending Data -----");
  Serial.println(postData);

  // Send HTTP POST request
  client.println("POST /micro03/sensordata.php HTTP/1.1");   // ✅ corrected path
  client.println("Host: " + String(host));
  client.println("Content-Type: application/x-www-form-urlencoded");
  client.print("Content-Length: ");
  client.println(postData.length());
  client.println();
  client.print(postData);

  // Wait for server response
  while (client.connected() || client.available()) {
    if (client.available()) {
      String line = client.readStringUntil('\n');
      Serial.println(line);
    }
  }

  client.stop();
  Serial.println("Data sent successfully!");
}
