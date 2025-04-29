<x-layouts.user title="Dashboard">

    <div class="max-w-4xl mx-auto py-10 px-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            
            <!-- Profile Banner -->
            <div class="relative">
                <div class="h-40 bg-indigo-600">
                    <img src="https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1350&q=80" 
                         alt="Earth from Space Banner" 
                         class="w-full h-full object-cover opacity-70">
                </div>

                <!-- Avatar -->
                <div class="absolute inset-x-0 top-24 flex justify-center">
                    <div class="w-32 h-32 rounded-full bg-indigo-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-white dark:border-gray-800 shadow-lg">
                        {{ Auth::user()->initials() }}
                    </div>
                </div>
            </div>

            <!-- Add extra space below the banner for avatar -->
            <div class="mt-20 text-center px-6 pb-8">
                <h2 class="text-3xl font-semibold text-gray-900 dark:text-white">
                    {{ Auth::user()->name }}
                </h2>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    {{ Auth::user()->email }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Role: <span class="font-medium">{{ ucfirst(Auth::user()->role) }}</span>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Member since {{ Auth::user()->created_at->format('F Y') }}
                </p>
            </div>

        </div>
    </div>

</x-layouts.user>
