<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')->constrained('categories')->onDelete('set null');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')->constrained('categories')->onDelete('set null');
        });

        // Migrate existing categories
        $contentCategories = DB::table('contents')->whereNotNull('category')->distinct()->pluck('category');
        $courseCategories = DB::table('courses')->whereNotNull('category')->distinct()->pluck('category');
        
        $allCategories = collect($contentCategories)->merge($courseCategories)->unique();

        foreach ($allCategories as $catName) {
            if (empty($catName)) continue;
            
            $categoryId = DB::table('categories')->insertGetId([
                'name' => $catName,
                'slug' => Str::slug($catName) . '-' . rand(100, 999), // Add randomness just in case of collisions
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('contents')->where('category', $catName)->update(['category_id' => $categoryId]);
            DB::table('courses')->where('category', $catName)->update(['category_id' => $categoryId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
