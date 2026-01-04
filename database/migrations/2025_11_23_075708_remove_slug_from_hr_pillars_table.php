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
        Schema::table('hr_pillars', function (Blueprint $table) {
            // Drop 'slug' column only if it exists
            if (Schema::hasColumn('hr_pillars', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_pillars', function (Blueprint $table) {
            // Recreate 'slug' column if needed
            if (!Schema::hasColumn('hr_pillars', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
        });
    }
};
