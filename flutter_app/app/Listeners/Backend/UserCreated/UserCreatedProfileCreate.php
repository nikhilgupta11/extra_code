<?php

namespace App\Listeners\Backend\UserCreated;

use App\Events\Backend\UserCreated;
use App\Models\Userprofile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UserCreatedProfileCreate implements ShouldQueue
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
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        $userprofile = new Userprofile();
        $userprofile->user_id = $user->id;
        $userprofile->gender = null;
        $userprofile->date_of_birth = null;
        $userprofile->status = ($user->status > 0) ? $user->status : 0;
        $userprofile->created_at = $user->created_at;
        $userprofile->updated_at = $user->updated_at;
        $userprofile->deleted_at = $user->deleted_at;
        $userprofile->save();

        // Clear Cache
        \Artisan::call('cache:clear');
    }
}
