<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        // sementara dummy dulu
        $recipes = [
            [
                'title' => 'Brownies Kukus',
                'similarity' => 0.82,
                'ingredients' => 'telur, tepung, coklat',
                'steps' => 'Campur bahan lalu kukus'
            ],
            [
                'title' => 'Pancake Coklat',
                'similarity' => 0.76,
                'ingredients' => 'telur, tepung, susu',
                'steps' => 'Campur dan goreng'
            ]
        ];

        return view('result', compact('ingredients', 'recipes'));
    }
}
