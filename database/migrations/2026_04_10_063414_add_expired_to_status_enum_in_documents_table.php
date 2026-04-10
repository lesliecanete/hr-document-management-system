<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddExpiredToStatusEnumInDocumentsTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('active', 'expiring_soon', 'archived', 'expired') DEFAULT 'active'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('active', 'expiring_soon', 'archived') DEFAULT 'active'");
    }
}