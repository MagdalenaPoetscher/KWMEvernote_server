<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // test user
        $user = new User;
        $user->firstname = 'Max';
        $user->lastname = 'Mustermann';
        $user->email = 'test@gmail.com';
        $user->password = bcrypt('secret');
        $user->save();

    }
}
