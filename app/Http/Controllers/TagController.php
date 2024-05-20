<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    public function index():JsonResponse{
        $tag = Tag::with('notes', 'todos')->get();
        return response()->json($tag, 200);
    }
    public function findById(string $id):JsonResponse{
        $tag = Tag::where('id', $id)->with(['notes', 'todos'])->first();
        return $tag != null ? response()->json($tag, 200) : response()->json(null, 200);
    }

    public function checkId(string $id):JsonResponse{
        $tag = Tag::where('id', $id)->first();
        return $tag!=null ? response()->json(true, 200) : response()->json(false, 200);
    }

    public function findBySearchTerm(string $searchTerm):JsonResponse{
        $tags = Tag::with(['user', 'todos', 'notes'])
            ->where('title', 'LIKE', '%'.$searchTerm.'%')->get();
            /*Beziehung zu Autor*/
            /*->orWhereHas('authors', function ($query) use ($searchTerm){
                $query->where('firstName', 'LIKE', '%'.$searchTerm.'%')
                    ->orWhere('lastName', 'LIKE', '%'.$searchTerm.'%');
            })->get();*/
        return response()->json($tags, 200);
    }


    public function save(Request $request):JsonResponse{
        $request = $this->parseRequest($request);
        //Starten eine DB Transaktion
        DB::beginTransaction();
        try{
            $tag = Tag::create($request->all());
            if(isset($request['note']) && is_array($request['note'])) {
                foreach ($request['note'] as $n) {
                    $note = Note::firstOrNew(['title' => $n['title']]);
                    $tag->notes()->save($note);
                }
            }if(isset($request['todo']) && is_array($request['todo'])){
                foreach ($request['todo'] as $t){
                    $todo = Todo::firstOrNew(['title' => $t['todo']]);
                    $tag->todos()->save($todo);
                }
            }
            DB::commit();
            return response()->json($tag, 201);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json("saving list failed ". $e->getMessage(), 420);
        }
    }

    public function update(Request $request):JsonResponse{
        DB::beginTransaction();
        try{
            $id = $request->route('id');
            $tag = Tag::all()->find($id);
            $tag->update($request->all());
            DB::commit();
            return response()->json($tag);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json("updating tag failed ". $e->getMessage(), 420);
        }
    }

    public function delete(string $id):JsonResponse{
        $tag = Tag::where('id', $id)->first();
        if($tag!=null){
            $tag->delete();
            return response()->json('tag ('. $id . ') successfully deleted', 200);
        } else{
            return response()->json("could not delete tag - it does not exist ", 422);
        }
    }

    private function parseRequest(Request $request):Request{
        $date = new DateTime($request->created_at);
        $request['created_at'] = $date->format('Y-m-d H:i:s');
        return $request;
    }
}
