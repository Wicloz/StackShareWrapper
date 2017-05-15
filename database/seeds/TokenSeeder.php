<?php

use App\AuthToken;
use Illuminate\Database\Seeder;

class TokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $token = env('UPLOAD_TOKEN', null);
        AuthToken::where('token', '!=', $token)->delete();
        AuthToken::firstOrCreate([
            'token' => $token,
        ]);
    }
}
