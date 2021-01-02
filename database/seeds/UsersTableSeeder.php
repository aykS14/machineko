<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'まちねこテスト',
            'email' => 'ayk.0104.snowcamellia@gmail.com',
            'password' => bcrypt('test.123')
        ]);
    }
}
