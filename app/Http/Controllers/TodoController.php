<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\Image;
use App\Models\Note;
use App\Models\Todo;
use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    public function index():JsonResponse{
        $todo = Todo::with(['note', 'images', 'tags', 'user'])->get();
        return response()->json($todo, 200);
    }

    public function findById(string $id):JsonResponse{
        $todo = Todo::where('id', $id)->with(['note', 'images', 'tags', 'user'])->first();
        return $todo != null ? response()->json($todo, 200) : response()->json(null, 200);
    }

    public function checkId(string $id):JsonResponse{
        $todo = Todo::where('id', $id)->first();
        return $todo!=null ? response()->json(true, 200) : response()->json(false, 200);
    }

    public function save(Request $request):JsonResponse{
        $request = $this->parseRequest($request);
        //Starten eine DB Transaktion
        DB::beginTransaction();
        try{
            $todo = Todo::create($request->all());

            if(isset($request['note']) && is_array($request['note'])) {
                foreach ($request['note'] as $n) {
                    $note = Note::firstOrNew(['title' => $n['title']]);
                    $todo->notes()->save($note);
                }
            }
            if (isset($request['images']) && is_array($request['images'])){
                foreach ($request['images'] as $img){
                    $image = Image::firstOrNew(['url'=>$img['url'],'title'=>$img['title']]);
                    $todo->images()->save($image);
                }
            }
            if(isset($request['user'])){
                $us = $request['user'];
                $user = User::firstOrNew(['firstName'=>$us['firstName'],'lastName'=>$us['lastName'],'email'=>$us['email'],'password'=>$us['password']]);
                $todo->user()->save($user);
            }
            $todo->tags()->sync($request['tags']);
            $todo->save();

            DB::commit();
            return response()->json($todo, 201);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json("saving todo failed ". $e->getMessage(), 420);
        }
    }

    public function update(Request $request, string $id):JsonResponse{
        //Starten eine DB Transaktion
        DB::beginTransaction();
        try{
            $todo = Todo::with( 'user', 'tags', 'images')->where('id', $id)->first();
            if($todo!=null){
                $request = $this->parseRequest($request);
                $todo->update($request->all());

                //delete all old notes
                //$todo->note()->delete();

                /*if(isset($request['note']) && is_array($request['note'])) {
                    foreach ($request['note'] as $n) {
                        $note = Note::firstOrNew(['title' => $n['title']]);
                        $todo->notes()->save($note);
                    }
                }*/
                if (isset($request['images']) && is_array($request['images'])){
                    foreach ($request['images'] as $img){
                        $image = Image::firstOrNew(['url'=>$img['url'],'title'=>$img['title']]);
                        $todo->images()->save($image);
                    }
                }
                /*if(isset($request['user'])){
                    $us = $request['user'];
                    $user = User::firstOrNew(['firstName'=>$us['firstName'],'lastName'=>$us['lastName'],'email'=>$us['email'],'password'=>$us['password']]);
                    $todo->user()->save($user);
                }*/
                $todo->tags()->sync($request['tags']);
                $todo->save();
            }
            DB::commit();
            $todo = todo::with( 'user', 'tags', 'images')->
            where('id', $id)->first();
            return response()->json($todo, 201);

        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json("updating todo failed ". $e->getMessage(), 420);
        }
    }

    public function delete(string $id):JsonResponse{
        $todo = Todo::where('id', $id)->first();
        if($todo!=null){
            $todo->delete();
            return response()->json('todo ('. $id . ') successfully deleted', 200);
        } else{
            return response()->json("could not delete todo - it does not exist ", 422);
        }
    }

    private function parseRequest(Request $request):Request{
        $date = new DateTime($request->due);
        $request['due'] = $date->format('Y-m-d H:i:s');
        return $request;
    }
}
