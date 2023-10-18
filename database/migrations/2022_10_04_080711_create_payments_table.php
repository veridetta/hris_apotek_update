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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employees_id')->constrained('employees');
            $table->integer('month');
            $table->integer('year');
            $table->integer('lembur');
            $table->integer('telat');
            $table->integer('tidak_masuk');
            $table->integer('makan');
            $table->integer('transport');
            $table->integer('potongan');
            $table->integer('payment');
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
        Schema::dropIfExists('payments');
    }
};
