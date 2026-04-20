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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type');
            $table->foreignId('parent_id')->nullable()->constrained('destinations')->nullOnDelete();
            $table->timestamps();

            $table->index(['type', 'parent_id']);
        });

        Schema::create('destinationables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->morphs('destinationable');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(
                ['destination_id', 'destinationable_type', 'destinationable_id'],
                'destinationables_unique_link'
            );
            $table->index(
                ['destinationable_type', 'destinationable_id', 'position'],
                'destinationables_lookup_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinationables');
        Schema::dropIfExists('destinations');
    }
};
