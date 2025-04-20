<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-time AQI Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
        }

        #map {
            height: 100vh;
            width: 100%;
            transition: all 0.3s ease;
        }

        /* Popup styling */
        .leaflet-popup-content {
            font-size: 1rem;
            color: #fff;
            padding: 20px;
            background: linear-gradient(45deg, #333, #555);
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            max-width: 350px;
            min-width: 250px;
            transition: transform 0.3s ease-in-out;
        }

        .leaflet-popup-content:hover {
            transform: scale(1.05);
        }

        .leaflet-popup-content h4 {
            font-size: 1.4rem;
            color: #fff;
            margin-bottom: 10px;
        }

        .leaflet-popup-content p {
            font-size: 1rem;
            margin: 5px 0;
            color: #eee;
        }

        /* Footer Styles */
        #footer {
            background: #222;
            color: #fff;
            padding: 20px 30px;
            font-size: 1rem;
            text-align: center;
            border-top: 2px solid #444;
        }

        #footer h3 {
            font-size: 1.6rem;
            margin-bottom: 15px;
            color: #fff;
        }

        #footer .aqi-levels {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
        }

        #footer .aqi-level {
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            color: #fff;
            transition: transform 0.3s ease-in-out;
            position: relative;
        }

        #footer .aqi-level:hover {
            transform: scale(1.1);
        }

        #footer .level-healthy {
            background-color: green;
        }

        #footer .level-moderate {
            background-color: yellow;
        }

        #footer .level-unhealthy {
            background-color: orange;
        }

        #footer .level-hazardous {
            background-color: red;
        }

        #footer p {
            font-size: 1rem;
            color: #bbb;
            margin-top: 10px;
        }

        /* AQI Circle Animations */
        .leaflet-circle {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .leaflet-circle:hover {
            transform: scale(1.3);
            opacity: 0.8;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            #footer .aqi-levels {
                flex-direction: column;
                gap: 10px;
            }

            #footer .aqi-level {
                width: 80%;
                margin: 0 auto;
            }
        }

        /* Tooltip styling for location name */
        .leaflet-tooltip {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px;
            border-radius: 5px;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Tooltip for AQI level descriptions */
        .aqi-level-tooltip {
            position: absolute;
            top: -100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            max-width: 300px;
            display: none;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            font-size: 0.9rem;
        }

        .aqi-level-tooltip img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .aqi-level:hover .aqi-level-tooltip {
            display: block;
        }

    </style>
</head>
<body>
    <div id="map"></div>

    <div id="footer">
        <h3>AQI Levels and Guidelines</h3>
        <div class="aqi-levels">
            <div class="aqi-level level-healthy">
                <h4>Healthy</h4>
                <p>AQI: 0-50</p>
                <div class="aqi-level-tooltip">
                    <img src="" alt="Healthy">
                    <span>Air quality is considered satisfactory, and air pollution poses little or no risk.</span>
                </div>
            </div>
            <div class="aqi-level level-moderate">
                <h4>Moderate</h4>
                <p>AQI: 51-100</p>
                <div class="aqi-level-tooltip">
                    <img src="" alt="Moderate">
                    <span>Air quality is acceptable; however, some pollutants may be a concern for a small number of people.</span>
                </div>
            </div>
            <div class="aqi-level level-unhealthy">
                <h4>Unhealthy</h4>
                <p>AQI: 101-150</p>
                <div class="aqi-level-tooltip">
                    <img src="" alt="Unhealthy">
                    <span>Everyone may begin to experience health effects; members of sensitive groups may experience health effects.</span>
                </div>
            </div>
            <div class="aqi-level level-hazardous">
                <h4>Hazardous</h4>
                <p>AQI: 151+</p>
                <div class="aqi-level-tooltip">
                    <img src="" alt="Hazardous">
                    <span>Health alert: everyone may experience serious health effects.</span>
                </div>
            </div>
        </div>
        <p><strong>Hazardous AQI Levels (151+):</strong> If the AQI level is hazardous, it is advised to avoid outdoor activities, stay indoors, and use air purifiers if available. Vulnerable individuals such as children, elderly people, and those with respiratory conditions should take extra precautions.</p>
    </div>

    <script>
        var map = L.map('map', {
            scrollWheelZoom: false
        }).setView([6.9375, 80.0167], 12); // Colombo Coordinates
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        function fetchSensorData() {
            $.get('/api/sensors', function(data) {
                if (!data || data.length === 0) {
                    return;
                }

                data.forEach(function(sensor) {
                    var color = getAQIColor(sensor.aqi);

                    var marker = L.marker([sensor.latitude, sensor.longitude], {
                        riseOnHover: true
                    }).addTo(map)
                    .bindPopup(createPopupContent(sensor));

                    var circle = L.circleMarker([sensor.latitude, sensor.longitude], {
                        color: color,
                        fillColor: color,
                        fillOpacity: 0.6,
                        radius: 12
                    }).addTo(map);

                    marker.on('mouseover', function() {
                        marker.bindTooltip(sensor.location, {
                            permanent: false,
                            direction: 'top',
                            className: 'leaflet-tooltip'
                        }).openTooltip();
                    });

                    marker.on('mouseout', function() {
                        marker.closeTooltip();
                    });

                    marker.on('click', function() {
                        marker.openPopup();
                    });

                    circle.on('mouseover', function() {
                        this.setRadius(20);
                    });

                    circle.on('mouseout', function() {
                        this.setRadius(12);
                    });
                });
            }).fail(function() {
                alert('Failed to load sensor data');
            });
        }

        function getAQIColor(aqi) {
            if (aqi <= 50) return 'green';
            else if (aqi <= 100) return 'yellow';
            else if (aqi <= 150) return 'orange';
            else return 'red';
        }

        function createPopupContent(sensor) {
            return `
                <div class="popup-content">
                    <h4>${sensor.location}</h4>
                    <p><b>AQI:</b> ${sensor.aqi}</p>
                    <p><b>CO2 Level:</b> ${sensor.co2_level} ppm</p>
                    <p><b>O2 Level:</b> ${sensor.o2_level} %</p>
                    <p><b>PM2.5:</b> ${sensor.pm25_level} µg/m³</p>
                    <p><b>PM10:</b> ${sensor.pm10_level} µg/m³</p>
                    <p><b>NO2 Level:</b> ${sensor.no2_level} ppb</p>
                    <p><b>SO2 Level:</b> ${sensor.so2_level} ppb</p>
                </div>
            `;
        }

        map.on('wheel', function(e) {
            if (!e.originalEvent.ctrlKey) {
                e.preventDefault();
            }
        });

        fetchSensorData();
    </script>
</body>
</html>
