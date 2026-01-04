<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_size');
            $table->string('file_type');
            $table->foreignId('document_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->date('document_date');
            $table->date('expiry_date');
            $table->enum('status', ['active', 'expiring_soon', 'archived'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'expiry_date']);
            $table->index(['document_type_id', 'employee_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};