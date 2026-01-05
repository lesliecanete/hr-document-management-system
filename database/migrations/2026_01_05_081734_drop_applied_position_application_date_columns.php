<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop if exists
        if (Schema::hasColumn('applicants', 'applied_position')) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->dropColumn('applied_position');
            });
        }
        
        if (Schema::hasColumn('applicants', 'application_date')) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->dropColumn('application_date');
            });
        }
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('applied_position')->nullable();
            $table->date('application_date')->nullable();
        });
    }
};