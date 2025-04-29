<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Air Quality Alerts</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center">

    <!-- Alerts Section -->
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 relative z-10">
        <h1 class="text-3xl font-semibold text-green-400">Air Quality Alerts</h1>
    </header>

    <section class="w-full lg:max-w-4xl text-center p-6">
        <h2 class="text-2xl font-bold mb-4">Current Alerts</h2>

        <!-- Loop through Alerts -->
        <div class="space-y-4">
            @foreach ($alerts as $alert)
                <div class="p-6 rounded-lg shadow-lg transition-transform transform hover:scale-105 
                    @if($alert->alert_level == 'Healthy') bg-green-500 @elseif($alert->alert_level == 'Moderate') bg-yellow-500 @elseif($alert->alert_level == 'Unhealthy') bg-red-600 @else bg-purple-700 @endif 
                    bg-opacity-80">
                    <h3 class="text-xl font-semibold 
                        @if($alert->alert_level == 'Healthy') text-white @elseif($alert->alert_level == 'Moderate') text-black @elseif($alert->alert_level == 'Unhealthy') text-white @else text-white @endif">
                        {{ $alert->alert_level }}
                    </h3>
                    <p class="text-lg mt-2">{{ $alert->message }}</p>
                    <p class="text-gray-300 mt-4">{{ $alert->created_at->diffForHumans() }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-12 text-center text-gray-400 relative z-10 px-4">
        <div class="text-sm space-x-4">
            <a href="{{ url('/terms') }}" class="text-green-400 hover:text-green-500 transition-colors">Terms and Conditions</a> |
            <a href="{{ url('/privacy') }}" class="text-green-400 hover:text-green-500 transition-colors">Privacy Policy</a> 
        </div>
        <div class="text-xs mt-4">
            <p>&copy; 2025 Air Quality Alerts. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
