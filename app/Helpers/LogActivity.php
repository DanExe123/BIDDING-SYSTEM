<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    public static function add($action, $location = null)
    {
        ActivityLog::create([
            'user_id'   => Auth::id(),
            'action'    => $action,
            'location'  => $location ?? request()->ip(),
            'user_role' => Auth::user()?->getRoleNames()->first()
        ]);
    }
}
