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
    Schema::table('employees', function (Blueprint $table) {
        $table->string('faceid')->nullable();
        $table->string('facereq')->nullable();
    });
}

public function down()
{
    Schema::table('employees', function (Blueprint $table) {
        $table->dropColumn('faceid');
        $table->dropColumn('facereq');
    });
}
};
