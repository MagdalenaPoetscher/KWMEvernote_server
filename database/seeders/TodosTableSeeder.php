<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $todo = new Todo();
        $todo->title = 'WEB-Projekt';
        $todo->description = 'DB anlegen';
        $todo->due = '2024-05-15';
        $user = User::first();
        $todo->user()->associate($user);
        $todo->save();
    }
}
