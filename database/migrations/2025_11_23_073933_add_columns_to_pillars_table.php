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
        Schema::table('pillars', function (Blueprint $table) {
            $table->string('name')->unique()->after('id');
            $table->text('description')->nullable()->after('name');
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pillars', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'is_active']);
        });
    }
};