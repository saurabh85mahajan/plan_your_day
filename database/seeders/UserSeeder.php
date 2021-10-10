<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::factory()
            ->hasProjects(3)
            ->create([
                'email' => 'user1@localhost.com',
                'password' => Hash::make('password')
            ]);
    }
}
