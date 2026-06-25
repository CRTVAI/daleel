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
        Schema::create('user_roadmaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); // The clean AI-corrected title
            $table->string('goal');  // The original search query
            $table->json('tools');   // Selected tool IDs
            $table->string('focus');
            $table->string('level');
            $table->json('curriculum'); // The phases/videos structure
            $table->integer('progress')->default(0); // 0-100%
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roadmaps');
    }
};
