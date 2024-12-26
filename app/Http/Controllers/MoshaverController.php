<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Moshaver;

class MoshaverController extends Controller
{
    public function getLastFiveMoshavers(): JsonResponse
    {
        $moshavers=Moshaver::orderBy('created_at','desc')
        ->take(5)
        ->get(['first_name','last_name','phone_number','email']);
        return response()->json($moshavers);
        }
}
