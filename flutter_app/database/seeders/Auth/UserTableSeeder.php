<?php

namespace Database\Seeders\Auth;

use App\Events\Backend\UserCreated;
use App\Models\User;
use App\Models\Userprofile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        Userprofile::truncate();
        Schema::disableForeignKeyConstraints();

        $faker = \Faker\Factory::create();

        // Add the master administrator, user id of 1
        $user = [
            'id'                => 1,
            'first_name'        => 'Super',
            'last_name'         => 'Admin',
            'name'              => 'Super Admin',
            'email'             => 'super@admin.com',
            'password'          => Hash::make('password'),
            'username'          => '100001',
            'mobile'            => $faker->phoneNumber,
            'avatar'            => 'img/default-avatar.jpg',
            'email_verified_at' => Carbon::now(),
            'type'              => 0,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ];
        $data = User::create($user);
        event(new UserCreated($data));

        foreach (range(1,20) as $key => $user_data) {
            $first = $faker->firstName;
            $last = $faker->lastName;
            $data = User::create([
                'first_name'        => $first,
                'last_name'         => $last,
                'name'              => $first." ".$last,
                'email'             => $faker->email,
                'password'          => Hash::make('password'),
                'username'          => '10000'.$key,
                'mobile'            => $faker->phoneNumber,
                'avatar'            => 'img/default-avatar.jpg',
                'email_verified_at' => Carbon::now(),
                'type'              => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]);
            $data->profile()->create(['gender'=>'Male','status'=>1]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
