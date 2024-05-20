<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\Image;
use App\Models\Note;
use App\Models\Tag;
use App\Models\Todo;
use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    //Get all notes that exist
    public function index():JsonResponse{
        $notes = Note::with(['todo', 'tags', 'images', 'user', 'catalogue'])->get();
        return response()->json($notes, 200);
    }

    public function findById(string $id):JsonResponse{
        $note = Note::where('id', $id)->with(['todo', 'tags', 'images', 'user', 'catalogue'])->first();
        return $note != null ? response()->json($note, 200) : response()->json(null, 200);
    }

    public function checkId(string $id):JsonResponse{
        $note = Note::where('id', $id)->first();
        return $note!=null ? response()->json(true, 200) : response()->json(false, 200);
    }

    //this function creates a new note
    public function save(Request $request):JsonResponse{
        $request = $this->parseRequest($request);
        //Starten eine DB Transaktion
        DB::beginTransaction();
        try{
            $note = Note::create($request->all());

            if(isset($request['todos']) && is_array($request['todos'])) {
                foreach ($request['todos'] as $t) {
                    $todo = Todo::where("id", $t)->first();
                    $note->todo()->save($todo);
                }
            }
            if (isset($request['images']) && is_array($request['images'])){
                foreach ($request['images'] as $img){
                    $image = Image::firstOrNew(['url'=>$img['url'],'title'=>$img['title']]);
                    $note->images()->save($image);
                }
            }
            if(isset($request['user'])){
                $us = $request['user'];
                $user = User::firstOrNew(['firstName'=>$us['firstName'],'lastName'=>$us['lastName'],'email'=>$us['email'],'password'=>$us['password']]);
                $note->user()->save($user);
            }
            //N:M Beziehung
            $note->tags()->sync($request['tags']);
            $note->save();

            DB::commit();
            return response()->json($note, 201);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json("saving list failed ". $e->getMessage(), 420);
        }
    }

    public function update(Request $request, string $id):JsonResponse{
        //Starten eine DB Transaktion
        DB::beginTransaction();
        try{
            $note = Note::with( ['todo', 'tags', 'images', 'user', 'catalogue'])->
            where('id', $id)->first();

            if($note!=null){
                $request = $this->parseRequest($request);
                $note->update($request->all());

                //delete all old todos
                //$note->todos()->delete();

                if(isset($request['todos']) && is_array($request['todos'])) {
                    foreach ($request['todos'] as $t) {
                        $todo = Todo::where("id", $t)->first();
                        $note->todo()->save($todo);
                    }
                }
                if (isset($request['images']) && is_array($request['images'])){
                    foreach ($request['images'] as $img){
                        $image = Image::firstOrNew(['url'=>$img['url'],'title'=>$img['title']]);
                        $note->images()->save($image);
                    }
                }
                if(isset($request['user'])){
                    $us = $request['user'];
                    $user = User::firstOrNew(['firstName'=>$us['firstName'],'lastName'=>$us['lastName'],'email'=>$us['email'],'password'=>$us['password']]);
                    $note->user()->save($user);
                }
                //$note->tags()->delete();
                /*if (isset($request['tags']) && is_array($request['tags'])) {
                    foreach ($request['tags'] as $t) {
                        $tag = Tag::firstOrNew(['title' => $t['title']]);
                        $note->tags()->save($tag);
                    }
                }*/

                //update tags
                $tag_ids = [];
                if (isset($request['tags']) && is_array($request['tags'])) {
                    foreach ($request['tags'] as $t) {
                        array_push($tag_ids, $t);
                    }
                }
                $note->tags()->sync($tag_ids);
                //N:M Beziehung
                //$note->tags()->sync($request['tags']);
                $note->save();
            }
            DB::commit();
            $note = Note::with( 'todo', 'tags', 'user', 'images', 'catalogue' )->
            where('id', $id)->first();
            return response()->json($note, 201);

        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json("updating list failed ". $e->getMessage(), 420);
        }
    }

    public function delete(string $id):JsonResponse{
        $note = Note::where('id', $id)->first();
        if($note!=null){
            $note->delete();
            return response()->json('note ('. $id . ') successfully deleted', 200);
        } else{
            return response()->json("could not delete note - it does not exist ", 422);
        }
    }

    private function parseRequest(Request $request1):Request{

        $date = new DateTime($request1->created_at);
        $request1['created_at'] = $date->format('Y-m-d H:i:s');
        return $request1;
    }

    private function parseRequest2(Request $request2):Request{

        $date = new DateTime($request2->updated_at);
        $request2['updated_at'] = $date->format('Y-m-d H:i:s');
        return $request2;

    }
}
