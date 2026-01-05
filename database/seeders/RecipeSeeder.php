<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        // Path to CSV file - adjust if needed
        $csvPath = storage_path('app/recipes.csv'); // Or wherever you place the CSV

        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: $csvPath");
            return;
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Skip header

        while (($row = fgetcsv($file)) !== false) {
            Recipe::create([
                'title' => $row[0] ?? '',
                'ingredients' => $row[1] ?? '',
                'steps' => $row[2] ?? '',
                'url' => $row[3] ?? null,
                // Add other fields if available in CSV
            ]);
        }

        fclose($file);

        $this->command->info('Recipes imported successfully!');
    }
}