<?php

namespace Database\Seeders;

use App\Models\Catalogue;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CataloguesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalogue = new Catalogue();
        $catalogue->name = 'Erste Liste';
        $user = User::first();
        $catalogue->user()->associate($user);
        $catalogue->save();

        //add note
        $note1 = new Note();
        $note1->title = "Erste Notiz";
        $note1->description = "Erste Beschreibung meiner Notiz";
        $note1->user()->associate($user);


        $note2 = new Note();
        $note2->title = "Erste Notiz";
        $note2->description = "Erste Beschreibung meiner Notiz";
        $note2->user()->associate($user);


        $catalogue->notes()->saveMany([$note1, $note2]);
        $catalogue->save();

    }
}
