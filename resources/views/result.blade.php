<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Rekomendasi - Sistem Rekomendasi Resep</title>
    @vite('resources/css/app.css')
</head>
    <body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-gray-800 mb-6 text-center">Hasil Rekomendasi</h2>
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <p class="text-lg text-gray-700"><strong>Bahan yang dimasukkan:</strong> {{ $ingredients }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($recipes as $recipe)
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3">{{ $recipe['title'] }}</h3>
                    <div class="mb-4">
                        <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                            Similarity: {{ $recipe['similarity'] }}
                        </span>
                    </div>
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-700 mb-2">Bahan:</h4>
                        <p class="text-gray-600">{{ $recipe['ingredients'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-gray-700 mb-2">Langkah:</h4>
                        <p class="text-gray-600 whitespace-pre-line">{{ $recipe['steps'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="/input" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300">Cari Lagi</a>
        </div>
    </div>
</body>
</html>
