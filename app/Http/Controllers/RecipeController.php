<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecipeController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function input()
    {
        return view('input');
    }

    public function result(Request $request)
    {
        $ingredients = $request->ingredients;

        // Call API Python
        $response = \Illuminate\Support\Facades\Http::post('http://localhost:5000/recommend', [
            'ingredients' => $ingredients
        ]);

        if ($response->successful()) {
            $recipes = $response->json();
        } else {
            // Fallback dummy jika API gagal
            $recipes = [
                [
                    'title' => 'Error: API tidak tersedia',
                    'similarity' => 0,
                    'ingredients' => '',
                    'steps' => ''
                ]
            ];
        }

        return view('result', compact('ingredients', 'recipes'));
    }

    public function mba()
    {
        // Load MBA rules from CSV (assuming file exists)
        $rules = [];
        if (file_exists(storage_path('app/mba_rules.csv'))) {
            $csv = array_map('str_getcsv', file(storage_path('app/mba_rules.csv')));
            array_shift($csv); // Remove header
            $rules = $csv;
        }

        return view('mba', compact('rules'));
    }
}
