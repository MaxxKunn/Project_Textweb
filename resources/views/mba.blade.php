<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Basket Analysis - Sistem Rekomendasi Resep</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-gray-800 mb-6 text-center">Market Basket Analysis</h2>
        <p class="text-lg text-gray-700 mb-8 text-center">Asosiasi bahan yang sering muncul bersama dalam resep</p>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2 text-left">Antecedents</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Consequents</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Support</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Confidence</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Lift</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rules as $rule)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $rule[0] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $rule[1] }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ number_format($rule[2], 3) }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ number_format($rule[3], 3) }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ number_format($rule[4], 3) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-center mt-8">
            <a href="/" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">Kembali ke Home</a>
        </div>
    </div>
</body>
</html>