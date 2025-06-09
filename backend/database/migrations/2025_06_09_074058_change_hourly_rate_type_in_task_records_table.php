<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_records', function (Blueprint $table) {
            $table->decimal('hourly_rate', 12, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('task_records', function (Blueprint $table) {
            $table->decimal('hourly_rate', 8, 2)->change(); 
        });
    }
};
