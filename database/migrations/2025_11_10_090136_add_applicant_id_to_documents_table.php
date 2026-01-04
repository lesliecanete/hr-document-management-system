<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Add applicant_id column
            $table->foreignId('applicant_id')->nullable()->after('document_type_id')->constrained()->onDelete('cascade');
            
            // Remove employee_id column if it exists
            if (Schema::hasColumn('documents', 'employee_id')) {
                $table->dropForeign(['employee_id']);
                $table->dropColumn('employee_id');
            }
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Remove applicant_id
            $table->dropForeign(['applicant_id']);
            $table->dropColumn('applicant_id');
            
            // Re-add employee_id if needed (for rollback)
            if (!Schema::hasColumn('documents', 'employee_id')) {
                $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade');
            }
        });
    }
};