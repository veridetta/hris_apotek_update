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
    Schema::table('settings', function (Blueprint $table) {
        $table->string('lokasi')->nullable();
        $table->float('lat')->nullable();
        $table->float('lng')->nullable();
        $table->integer('radius')->nullable();
    });
}

public function down()
{
    Schema::table('settings', function (Blueprint $table) {
        $table->dropColumn('lokasi');
        $table->dropColumn('lat');
        $table->dropColumn('lng');
        $table->dropColumn('radius');
    });
}

};
