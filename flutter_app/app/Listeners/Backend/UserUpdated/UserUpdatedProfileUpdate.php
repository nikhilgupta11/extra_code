<?php

namespace App\Listeners\Backend\UserUpdated;

use App\Events\Backend\UserUpdated;
use App\Models\Userprofile;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserUpdatedProfileUpdate implements ShouldQueue
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
     * @param  UserUpdated  $event
     * @return void
     */
    public function handle(UserUpdated $event)
    {
        $user = $event->user;

        $userprofile = Userprofile::withTrashed()->where('user_id', '=', $user->id)->first();

        $userprofile->gender = $user->gender;
        $userprofile->date_of_birth = $user->date_of_birth;
        $userprofile->status = $user->status;
        $userprofile->updated_at = $user->updated_at;
        $userprofile->deleted_at = $user->deleted_at;
        $userprofile->save();

        // Clear Cache
        \Artisan::call('cache:clear');
    }
}
