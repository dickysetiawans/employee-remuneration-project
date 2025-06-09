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
         Schema::create('task_records', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->text('task_description');
            $table->date('date');
            $table->float('hours_spent');
            $table->float('hourly_rate');
            $table->float('additional_charges')->default(0);
            $table->float('total_remuneration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_records');
    }
};
