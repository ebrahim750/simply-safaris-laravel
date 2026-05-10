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
        Schema::create('tour_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('tour_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->string('main_image');
            $table->json('gallery_images')->nullable();
            $table->string('external_url')->nullable();
            $table->string('external_link_button_text')->nullable();
            $table->boolean('show_external_booking')->default(false);
            $table->integer('duration_days')->nullable();
            $table->longText('overview')->nullable();
            $table->longText('pricing_detailed')->nullable();
            $table->boolean('is_hidden')->default(false)->index();
            $table->boolean('is_featured_on_home')->default(false)->index();
            $table->boolean('is_featured_as_seasonal_recommendation')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('tour_itinerary_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->string('title');
            $table->longText('content')->nullable();
            $table->timestamps();

            $table->index(['tour_id', 'position']);
        });

        Schema::create('tour_activity_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tour_activity_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['tour_id', 'tour_activity_id']);
            $table->index(['tour_id', 'position']);
        });

        Schema::create('tour_category_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tour_category_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['tour_id', 'tour_category_id']);
            $table->index(['tour_id', 'position']);
        });

        Schema::create('tour_inclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->text('text');
            $table->timestamps();

            $table->index(['tour_id', 'position']);
        });

        Schema::create('tour_exclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->text('text');
            $table->timestamps();

            $table->index(['tour_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_exclusions');
        Schema::dropIfExists('tour_inclusions');
        Schema::dropIfExists('tour_category_pivot');
        Schema::dropIfExists('tour_activity_pivot');
        Schema::dropIfExists('tour_itinerary_items');
        Schema::dropIfExists('tours');
        Schema::dropIfExists('tour_activities');
        Schema::dropIfExists('tour_categories');
    }
};
