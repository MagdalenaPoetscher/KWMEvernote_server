<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $user = User::with(['notes', 'catalogue', 'tags'])->get();
        return response()->json($user, 200);
    }

    public function findByID(string $id): JsonResponse
    {
        $user = User::where('id', $id)->with([])->first();
        return $user != null ? response()->json($user, 200) : response()->json(null, 200);
    }
}
