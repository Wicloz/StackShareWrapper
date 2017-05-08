<?php

use Illuminate\Database\Seeder;
use App\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::where('email', '!=', env('ADMIN_EMAIL'))->delete();

        if (!empty(env('ADMIN_EMAIL')) && !empty(env('ADMIN_PASSWORD'))) {
            User::updateOrCreate(
                ['email' => env('ADMIN_EMAIL')],
                ['password' => bcrypt(env('ADMIN_PASSWORD'))]
            );
        }
    }
}
