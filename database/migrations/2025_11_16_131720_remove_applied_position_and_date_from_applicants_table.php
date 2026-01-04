<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn(['applied_position', 'application_date']);
        });
    }

    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('applied_position');
            $table->date('application_date');
        });
    }
};