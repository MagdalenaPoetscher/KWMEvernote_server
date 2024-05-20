<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Note;
use App\Models\Tag;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $note = new Note();
        $note->title = 'Geburtstagsnotiz';
        $note->description = 'Ich wünsche mir ein Buch.';
        $user = User::first();
        $note->user()->associate($user);
        //in die Datenbank speichern
        $note->save();

        $note1 = new Note();
        $note1->title = 'Test Notize';
        $note1->description = 'blalba';
        //$user = User::first();
        $note1->user()->associate($user);
        $note1->save();


        $todo = new Todo();
        $todo->title = 'Todo 1';
        $todo->description = 'Beschreibung';
        $todo->due = '2024-05-15';
        //$user1 = User::first();
        $todo->user()->associate($user);
        $todo->save();

        $todo1 = new Todo();
        $todo1->title = 'Todo 2';
        $todo1->description = 'Beschreibung';
        $todo1->due = '2024-05-15';
        //$user2 = User::first();
        $todo1->user()->associate($user);
        $todo1->save();

        $note1->todo()->saveMany([$todo, $todo1]);

        $tag = new Tag();
        $tag->title = 'Tag 1';
        //$user3 = User::first();
        $tag->user()->associate($user);
        $tag->save();

        $tag2 = new Tag();
        $tag2->title = 'Tag 2';
        //$user4 = User::first();
        $tag2->user()->associate($user);
        $tag2->save();

        $note1->tags()->saveMany([$tag, $tag2]);

        $image1 = new Image();
        $image1->title = "Cover 1";
        $image1->url = "https://m.media-amazon.com/images/I/71pio-YV3XL._SY466_.jpg";

        $image = new Image();
        $image->title = "Testbild";
        $image->url = "https://m.media-amazon.com/images/I/51193ZLozAL._SY445_SX342_.jpg";
        $note1->images()->saveMany([$image, $image1]);
        $note1->save();
    }
}
