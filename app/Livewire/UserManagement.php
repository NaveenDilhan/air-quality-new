<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\BannedUser;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public function render()
    {
        // Fetch users excluding admins
        $users = User::where('role', '!=', 'admin')
                    ->latest()
                    ->paginate(10);

        return view('livewire.user-management', [
            'users' => $users,
        ]);
    }

    public function toggleBan($userId)
    {
        $user = User::findOrFail($userId);

        $banned = BannedUser::where('user_id', $user->id)->first();

        if ($banned) {
            // Unban user
            $banned->delete();
            session()->flash('message', 'User has been unbanned.');
        } else {
            // Ban user
            BannedUser::create([
                'user_id' => $user->id,
            ]);
            session()->flash('message', 'User has been banned.');
        }
    }
}
