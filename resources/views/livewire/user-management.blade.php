<div class="p-6">
    <h2 class="text-2xl font-bold mb-4 text-white">User Management</h2>

    @if (session()->has('message'))
        <div class="bg-green-600 text-white p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Users Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-x-auto p-4">
        <table class="min-w-full text-white">
            <thead>
                <tr class="text-left border-b border-zinc-700">
                    <th class="py-2">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-zinc-700">
                        <td class="py-2">{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $isBanned = \App\Models\BannedUser::where('user_id', $user->id)->exists();
                            @endphp

                            @if ($isBanned)
                                <span class="px-2 py-1 text-red-500 bg-red-100 dark:bg-red-700 dark:text-red-100 rounded text-xs">Banned</span>
                            @else
                                <span class="px-2 py-1 text-green-500 bg-green-100 dark:bg-green-700 dark:text-green-100 rounded text-xs">Active</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <button wire:click="toggleBan({{ $user->id }})" 
                                    class="px-3 py-1 rounded text-white 
                                        {{ $isBanned ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                                {{ $isBanned ? 'Unban' : 'Ban' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-zinc-400 py-4">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
