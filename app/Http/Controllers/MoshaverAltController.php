<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoshaverAlt;

class MoshaverAltController extends Controller
{
    public function index()
    {
        $moshavers = MoshaverAlt::all();
        $formattedMoshavers = $moshavers->map(function ($moshaver) {
            $moshaver->best_students = json_decode($moshaver->best_students, true);
            $moshaver->contact = json_decode($moshaver->contact, true);
            unset($moshaver->id);
            unset($moshaver->created_at);
            unset($moshaver->updated_at);

            return $moshaver;
        });
        return response()->json($formattedMoshavers);
    }
}
