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
        Schema::create('employee_salary', function (Blueprint $table) {
            $table->increments('id', true);
            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employee');
            $table->decimal('salary', 16, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary');
    }
};
