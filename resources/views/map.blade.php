<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colombo Air Quality Monitoring</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Livewire styles -->
    @livewireStyles
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Colombo Air Quality Monitoring</h1>

        <!-- Map container -->
        <div id="air-quality-map" style="height: 600px;"></div>

        <!-- Air Quality Data -->
        <div id="air-quality-data" class="my-4">
            <h4>Air Quality Data</h4>
            <div>
                <span id="air-quality-index">AQI: Loading...</span>
                <p id="pollutant-level">Pollutants: Loading...</p>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Your script to load the map and air quality data -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize the map
            const map = L.map('air-quality-map').setView([6.9271, 79.8612], 12); // Colombo coordinates

            // Add OpenStreetMap tiles to the map
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add a marker for Colombo
            const colomboMarker = L.marker([6.9271, 79.8612]).addTo(map);
            colomboMarker.bindPopup("Colombo, Sri Lanka").openPopup();

            // Fetch real-time air quality data (you need an API that provides this info)
            fetch('https://api.waqi.info/feed/colombo/?token=YOUR_API_TOKEN')  // Replace with actual API endpoint and token
                .then(response => response.json())
                .then(data => {
                    const airQualityIndex = data.data.aqi;
                    const pollutants = data.data.iaqi;

                    // Update the map with AQI data
                    document.getElementById('air-quality-index').textContent = `AQI: ${airQualityIndex}`;
                    document.getElementById('pollutant-level').textContent = `Pollutants: PM2.5: ${pollutants.pm25?.v || 'N/A'}, PM10: ${pollutants.pm10?.v || 'N/A'}`;

                    // Optionally, you can add markers for other locations based on API data
                })
                .catch(error => {
                    console.error('Error fetching air quality data:', error);
                    document.getElementById('air-quality-index').textContent = 'AQI: Error';
                    document.getElementById('pollutant-level').textContent = 'Pollutants: Error';
                });
        });
    </script>

    @livewireScripts
</body>
</html>
