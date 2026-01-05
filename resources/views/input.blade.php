<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukkan Bahan - Sistem Rekomendasi Resep</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-lg mx-auto w-full">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Masukkan Bahan</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/result" class="space-y-4">
            @csrf
            <div>
                <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-2">Bahan-bahan (pisahkan dengan koma)</label>
                <textarea name="ingredients" id="ingredients" rows="4" placeholder="telur, tepung, coklat" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300">Cari Rekomendasi</button>
        </form>
    </div>
</body>
</html>
