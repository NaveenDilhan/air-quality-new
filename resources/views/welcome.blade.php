<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colombo Air Quality Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        #map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .bg-overlay {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }
        .hover-zoom:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>
<body class="text-white flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col relative">
    <div id="map"></div>
    
    <!-- Background Overlay -->
    <div class="absolute inset-0 bg-overlay"></div>

    <!-- Header Section -->
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 relative z-10">
        @if (Route::has('login'))
            <nav class="flex items-center justify-between bg-black bg-opacity-70 px-6 py-4 rounded-lg">
                <h1 class="text-lg font-semibold text-green-400"> Colombo Air Quality Monitor</h1>
                <div class="flex gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md text-sm">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>
        @endif
    </header>

    <!-- Hero Section -->
    <main class="text-center bg-black bg-opacity-70 p-10 rounded-lg shadow-lg relative z-10">
        <h1 class="text-4xl font-bold text-green-400 mb-4">Real-time Air Quality Monitoring</h1>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto">Stay informed about air pollution levels in Colombo and make informed decisions for your health.</p>
        
        <!-- CTA Buttons -->
        <div class="mt-6 flex justify-center gap-4">
            <a href="{{ url('/map') }}" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg hover-zoom">
                View Live Data
            </a>
            <a href="#subscribe" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg hover-zoom">
                Subscribe Alerts
            </a>
        </div>
    </main>

    <!-- Air Quality Data Section -->
    <section id="data" class="mt-12 w-full lg:max-w-4xl relative z-10">
        <h2 class="text-3xl text-center text-white font-semibold mb-6">Current Air Quality</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-black bg-opacity-60 p-6 rounded-lg shadow-lg text-center hover-zoom">
                <h3 class="text-xl text-yellow-400">PM2.5</h3>
                <p class="text-3xl font-bold text-white">45 µg/m³</p>
                <p class="text-gray-300">Moderate</p>
            </div>
            <div class="bg-black bg-opacity-60 p-6 rounded-lg shadow-lg text-center hover-zoom">
                <h3 class="text-xl text-red-400">CO2</h3>
                <p class="text-3xl font-bold text-white">400 ppm</p>
                <p class="text-gray-300">High</p>
            </div>
            <div class="bg-black bg-opacity-60 p-6 rounded-lg shadow-lg text-center hover-zoom">
                <h3 class="text-xl text-green-400">Oxygen</h3>
                <p class="text-3xl font-bold text-white">21%</p>
                <p class="text-gray-300">Normal</p>
            </div>
        </div>
    </section>

    <!-- Subscription Section -->
    <section id="subscribe" class="mt-12 bg-black bg-opacity-70 p-8 rounded-lg text-center shadow-lg relative z-10">
        <h2 class="text-3xl font-semibold text-white">Get Notified</h2>
        <p class="text-gray-300 mt-2 mb-4">Subscribe to receive air quality updates and alerts directly to your inbox.</p>
        <form class="flex flex-col sm:flex-row gap-4 items-center justify-center">
            <input type="email" placeholder="Enter your email" class="px-4 py-2 text-black rounded-lg w-full sm:w-80">
            <button class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg hover-zoom">
                Subscribe
            </button>
        </form>
    </section>

    <!-- Footer -->
    <footer class="mt-12 text-center text-gray-400 relative z-10">
        <div class="flex flex-col items-center justify-center gap-4">
            <p class="text-sm">&copy; 2025 Colombo Air Quality Monitoring. All rights reserved.</p>
            <div class="text-sm">
                <a href="{{ url('/terms') }}" class="text-green-400 hover:text-green-500">Terms and Conditions</a>|
                <a href="{{ url('/privacy') }}" class="text-green-400 hover:text-green-500">Privacy Policy</a> 
            </div>
            <div class="mt-4 text-gray-300 text-sm">
                <p>By subscribing, you agree to receive air quality updates and alerts. You can unsubscribe at any time.</p>
            </div>
        </div>
    </footer>

    <!-- Leaflet Map Initialization -->
    <script>
        var map = L.map('map').setView([6.9271, 79.8612], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
    </script>
</body>
</html>
