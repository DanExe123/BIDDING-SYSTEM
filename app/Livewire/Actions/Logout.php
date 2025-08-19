<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\ActivityLog;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        
        $user = auth()->user();
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();
        
        if ($user) {
            ActivityLog::create([
                'user_id' => $user->id, // ✅ Use saved user object
                'action' => 'Logout',
                'location' => request()->ip(),
            ]);
        }


        return redirect('/');
    }
}
