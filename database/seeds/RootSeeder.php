<?php

use Illuminate\Database\Seeder;
use App\StackFolder;

class RootSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StackFolder::firstOrCreate([
            'name' => null,
            'parent_id' => null,
        ]);
    }
}
