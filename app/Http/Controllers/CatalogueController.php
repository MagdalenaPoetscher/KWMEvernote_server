<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\Note;
use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CatalogueController extends Controller
{
    public function index():JsonResponse{
      //load all catalogues(lists) with all relations with eager loading
        $catalogue = Catalogue::with('notes')->get();
        return response()->json($catalogue, 200);
    }

    public function findById(string $id):JsonResponse{
        $catalogue = Catalogue::where('id', $id)->with(['notes'])->first();
        return $catalogue != null ? response()->json($catalogue, 200) : response()->json(null, 200);
    }

    public function checkId(string $id):JsonResponse{
        $catalogue = Catalogue::where('id', $id)->first();
        return $catalogue!=null ? response()->json(true, 200) : response()->json(false, 200);
    }

    public function save(Request $request):JsonResponse{
        $request = $this->parseRequest($request);
        //Starten eine DB Transaktion
        DB::beginTransaction();
        try{
            $catalogue = Catalogue::create($request->all());
            if(isset($request['note']) && is_array($request['note'])) {
                foreach ($request['note'] as $n) {
                    $note = Note::firstOrNew(['title' => $n['title']]);
                    $catalogue->notes()->save($note);
                }
            }
            if(isset($request['user']) && is_array($request['user'])){
                foreach ($request['user'] as $user){
                    $user = User::firstOrNew(['firstname'=>$user['firstname'], 'lastname'=>$user['lastname']]);
                    $catalogue->users()->save($user);
                }
            }
            DB::commit();
            return response()->json($catalogue, 201);
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
            $catalogue = Catalogue::with( 'notes')->
            where('id', $id)->first();

            if($catalogue!=null){
                $request = $this->parseRequest($request);
                $catalogue->update($request->all());

                //delete all old notes
                //$catalogue->notes()->delete();

                if(isset($request['notes']) && is_array($request['notes'])) {
                    foreach ($request['notes'] as $n) {
                        $note = Note::firstOrNew(['title' => $n['title']]);
                        $catalogue->notes()->save($note);
                    }
                }

                /*$ids = [];
                if(isset($request['user']) && is_array($request['user'])){
                    foreach ($request['user'] as $u) {
                        array_push($ids, $u['id']);
                    }
                }*/
                //$catalogue->user()->sync($ids);
                $catalogue->save();
            }
            DB::commit();
            $catalogue1 = Catalogue::with( 'notes')->
            where('id', $id)->first();
            return response()->json($catalogue1, 201);

        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json("updating list failed ". $e->getMessage(), 420);
        }
    }

    public function delete(string $id):JsonResponse{
        $catalogue = Catalogue::where('id', $id)->first();
        if($catalogue!=null){
            $catalogue->delete();
            return response()->json('catalogue ('. $id . ') successfully deleted', 200);
        } else{
            return response()->json("could not delete catalogue - it does not exist ", 422);
        }
    }

    private function parseRequest(Request $request):Request{
        $date = new DateTime($request->created_at);
        $request['created_at'] = $date->format('Y-m-d H:i:s');
        return $request;
    }
}
