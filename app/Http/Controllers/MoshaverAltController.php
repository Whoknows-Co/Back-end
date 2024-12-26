<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoshaverAlt;

class MoshaverAltController extends Controller
{
    public function index()
    {
        // Fetch all records from the MoshaverAlt model
        $moshavers = MoshaverAlt::all();

        // Format and remove unnecessary fields
        $formattedMoshavers = $moshavers->map(function ($moshaver) {
            // Decode the JSON fields
            $moshaver->best_students = json_decode($moshaver->best_students, true);
            $moshaver->contact = json_decode($moshaver->contact, true);

            // Remove the unwanted fields
            unset($moshaver->id);
            unset($moshaver->created_at);
            unset($moshaver->updated_at);

            return $moshaver;
        });

        // Return the data as JSON
        return response()->json($formattedMoshavers);
    }
}
