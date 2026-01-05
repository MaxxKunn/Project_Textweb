<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('ingredients');
            $table->longText('steps')->nullable();
            $table->text('url')->nullable();

            $table->string('title_cleaned')->nullable();
            $table->integer('total_ingredients')->nullable();
            $table->text('ingredients_cleaned')->nullable();
            $table->integer('total_steps')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
