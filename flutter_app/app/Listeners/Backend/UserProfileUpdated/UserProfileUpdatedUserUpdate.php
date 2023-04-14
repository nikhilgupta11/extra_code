<?php

namespace App\Listeners\Backend\UserProfileUpdated;

use App\Events\Backend\UserProfileUpdated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserProfileUpdatedUserUpdate implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserProfileUpdated  $event
     * @return void
     */
    public function handle(UserProfileUpdated $event)
    {
        $user_profile = $event->user_profile;
        // Clear Cache
        \Artisan::call('cache:clear');
    }
}
