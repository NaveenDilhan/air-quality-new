<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-time AQI Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
            overflow-x: hidden;
        }

        #map {
            height: 100vh;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
        }

        /* Loading overlay */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: opacity 0.3s ease;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

        .leaflet-popup-content a {
            color: #00ccff;
            text-decoration: none;
            font-weight: bold;
        }

        .leaflet-popup-content a:hover {
            text-decoration: underline;
        }

        /* Footer Styles */
        #footer {
            background: #222;
            color: #fff;
            padding: 30px 40px;
            font-size: 1rem;
            text-align: center;
            border-top: 2px solid #444;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        #footer h3 {
            font-size: 1.6rem;
            margin-bottom: 20px;
            color: #fff;
        }

        #footer .aqi-levels {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        #footer .aqi-level {
            text-align: center;
            padding: 15px 20px;
            border-radius: 8px;
            color: #fff;
            transition: transform 0.3s ease-in-out;
            position: relative;
            width: 150px;
            margin: 0 10px;
            box-sizing: border-box;
            cursor: pointer;
        }

        #footer .aqi-level:hover, #footer .aqi-level:focus {
            transform: scale(1.1);
            outline: none;
        }

        #footer .level-healthy {
            background-color: green;
        }

        #footer .level-moderate {
            background-color: yellow;
            color: #333;
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
            margin-top: 15px;
        }

        #footer ul {
            list-style-type: none;
            text-align: left;
            margin: 0 auto;
            padding-left: 0;
            font-size: 0.9rem;
            max-width: 600px;
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

            #map {
                height: calc(100vh - 60px);
            }
        }

        /* Tooltip styling */
        .leaflet-tooltip {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px;
            border-radius: 5px;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Popup content styling */
        .aqi-popup {
            display: none;
            background-color: #333;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            max-width: 300px;
            text-align: left;
            font-size: 0.9rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div id="map">
        <div class="loading-overlay" id="loading-overlay">
            <div class="loading-spinner"></div>
        </div>
    </div>

    <div id="footer">
        <h3>AQI Levels and Guidelines</h3>
        <div class="aqi-levels" role="region" aria-label="AQI Level Information">
            <div class="aqi-level level-healthy" data-aqi="0-50" data-info="Air quality is considered satisfactory, and air pollution poses little or no risk." tabindex="0" role="button" aria-label="Healthy AQI Level">
                <h4>Healthy</h4>
                <p>AQI: 0-50</p>
                <div class="aqi-popup" role="tooltip"></div>
            </div>
            <div class="aqi-level level-moderate" data-aqi="51-100" data-info="Air quality is acceptable; however, some pollutants may be a concern for sensitive individuals." tabindex="0" role="button" aria-label="Moderate AQI Level">
                <h4>Moderate</h4>
                <p>AQI: 51-100</p>
                <div class="aqi-popup" role="tooltip"></div>
            </div>
            <div class="aqi-level level-unhealthy" data-aqi="101-150" data-info="Everyone may begin to experience health effects; sensitive groups may experience more serious effects." tabindex="0" role="button" aria-label="Unhealthy AQI Level">
                <h4>Unhealthy</h4>
                <p>AQI: 101-150</p>
                <div class="aqi-popup" role="tooltip"></div>
            </div>
            <div class="aqi-level level-hazardous" data-aqi="151+" data-info="Health alert: everyone may experience more serious health effects. Stay indoors and limit outdoor activities." tabindex="0" role="button" aria-label="Hazardous AQI Level">
                <h4>Hazardous</h4>
                <p>AQI: 151+</p>
                <div class="aqi-popup" role="tooltip"></div>
            </div>
        </div>

        <p><strong>Protect Yourself:</strong> If the AQI level is <span class="level-hazardous">Hazardous</span>, follow these precautions:</p>
        <ul>
            <li>💨 Stay indoors as much as possible.</li>
            <li>🧴 Use air purifiers if available.</li>
            <li>😷 Wear a high-quality N95 mask if you must go outside.</li>
            <li>🚫 Limit physical activities, especially strenuous ones.</li>
            <li>👶 Keep children, elderly, and individuals with respiratory conditions indoors.</li>
        </ul>

        <p><strong>Additional Tips for Moderate and Unhealthy AQI:</strong></p>
        <ul>
            <li>🌬️ Keep windows closed to prevent polluted air from entering your home.</li>
            <li>🚶‍♀️ If you have to go outside, limit time spent outdoors.</li>
            <li>💧 Drink plenty of water to help reduce the effects of air pollution.</li>
        </ul>
    </div>

    <script>
        // Configuration
        const CONFIG = {
            API_URL: '/api/sensors',
            MAP_CENTER: [6.9375, 80.0167], // Colombo Coordinates
            MAP_ZOOM: 12,
            MAX_RETRIES: 3,
            RETRY_DELAY: 2000,
            DEBOUNCE_TIME: 300
        };

        // Initialize map
        const map = L.map('map', {
            scrollWheelZoom: false,
            zoomControl: true
        }).setView(CONFIG.MAP_CENTER, CONFIG.MAP_ZOOM);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Utility functions
        const utils = {
            getAQIColor(aqi) {
                if (aqi <= 50) return 'green';
                if (aqi <= 100) return 'yellow';
                if (aqi <= 150) return 'orange';
                return 'red';
            },

            createPopupContent(sensor) {
                const historyUrl = "{{ route('history', ['sensor_id' => ':id']) }}".replace(':id', sensor.sensor_id);
                return `
                    <div class="popup-content" role="dialog" aria-label="Sensor Information">
                        <h4>${sensor.location}</h4>
                        <p><b>AQI:</b> ${sensor.aqi}</p>
                        <p><b>CO2 Level:</b> ${sensor.co2_level} ppm</p>
                        <p><b>O2 Level:</b> ${sensor.o2_level} %</p>
                        <p><b>PM2.5:</b> ${sensor.pm25_level} µg/m³</p>
                        <p><b>PM10:</b> ${sensor.pm10_level} µg/m³</p>
                        <p><b>NO2 Level:</b> ${sensor.no2_level} ppb</p>
                        <p><b>SO2 Level:</b> ${sensor.so2_level} ppb</p>
                        <p><a href="${historyUrl}" aria-label="View history for ${sensor.location}">View History</a></p>
                    </div>
                `;
            },

            debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            },

            showLoading(show) {
                $('#loading-overlay').css('display', show ? 'flex' : 'none');
            }
        };

        // Data fetching with retry logic
        async function fetchSensorData(attempt = 1) {
            utils.showLoading(true);
            try {
                const response = await $.get(CONFIG.API_URL);
                if (!response || response.length === 0) {
                    throw new Error('No data received');
                }
                renderSensors(response);
            } catch (error) {
                console.error(`Attempt ${attempt} failed:`, error);
                if (attempt < CONFIG.MAX_RETRIES) {
                    setTimeout(() => fetchSensorData(attempt + 1), CONFIG.RETRY_DELAY);
                } else {
                    alert('Failed to load sensor data after multiple attempts');
                }
            } finally {
                utils.showLoading(false);
            }
        }

        // Render sensors on map
        function renderSensors(data) {
            const layerGroup = L.layerGroup().addTo(map);

            data.forEach(sensor => {
                const color = utils.getAQIColor(sensor.aqi);

                // Create marker
                const marker = L.marker([sensor.latitude, sensor.longitude], {
                    riseOnHover: true,
                    keyboard: true
                }).addTo(layerGroup)
                    .bindPopup(utils.createPopupContent(sensor), {
                        closeButton: true,
                        autoClose: false
                    });

                // Create circle marker
                const circle = L.circleMarker([sensor.latitude, sensor.longitude], {
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.6,
                    radius: 12
                }).addTo(layerGroup);

                // Event handlers
                marker.on('mouseover', () => {
                    marker.bindTooltip(sensor.location, {
                        permanent: false,
                        direction: 'top',
                        className: 'leaflet-tooltip'
                    }).openTooltip();
                });

                marker.on('mouseout', () => {
                    marker.closeTooltip();
                });

                circle.on('mouseover', () => {
                    circle.setRadius(20);
                });

                circle.on('mouseout', () => {
                    circle.setRadius(12);
                });

                // Accessibility: Open popup on enter key
                marker.on('keypress', e => {
                    if (e.originalEvent.key === 'Enter') {
                        marker.openPopup();
                    }
                });
            });
        }

        // Map interaction
        map.on('wheel', e => {
            if (!e.originalEvent.ctrlKey) {
                e.preventDefault();
            }
        });

        // AQI level card interactions
        $(document).ready(() => {
            const debouncedHover = utils.debounce((element, show) => {
                const info = element.data('info');
                const popup = element.find('.aqi-popup');
                popup.html(`<p><i>${info}</i></p>`);
                popup.stop().fadeIn(show ? 200 : 0);
                if (!show) popup.hide();
            }, CONFIG.DEBOUNCE_TIME);

            $('.aqi-level').on('mouseenter focus', function() {
                debouncedHover($(this), true);
            }).on('mouseleave blur', function() {
                debouncedHover($(this), false);
            });

            // Keyboard accessibility for AQI levels
            $('.aqi-level').on('keypress', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    $(this).trigger('mouseenter');
                }
            });
        });

        // Initialize
        fetchSensorData();
    </script>
</body>
</html>