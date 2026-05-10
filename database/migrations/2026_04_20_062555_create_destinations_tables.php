<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('country_code', 2)->nullable();
            $table->text('overview')->nullable();
            $table->string('main_image')->nullable();
            $table->text('best_time_to_go')->nullable();
            $table->text('high_season')->nullable();
            $table->text('electrical')->nullable();
            $table->string('tourist_info')->nullable();
            $table->timestamps();
        });

        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('overview')->nullable();
            $table->string('main_image')->nullable();
            $table->timestamps();

            $table->index(['country_id', 'name']);
        });

        Schema::create('tour_place', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->foreignId('place_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['tour_id', 'place_id']);
            $table->index(['tour_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_place');
        Schema::dropIfExists('places');
        Schema::dropIfExists('countries');
    }
};
