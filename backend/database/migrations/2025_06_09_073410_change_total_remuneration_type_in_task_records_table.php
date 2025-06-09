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
            
            $table->decimal('total_remuneration', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('task_records', function (Blueprint $table) {
            $table->decimal('total_remuneration', 8, 2)->change();
        });
    }
};
