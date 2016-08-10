<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HookModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hooks_modules', function (Blueprint $table) {
            $table->integer('id_hook')->unsigned();
            $table->foreign('id_hook')->references('id')->on('hooks')->onDelete('cascade');
            $table->integer('id_module')->unsigned();
            $table->foreign('id_module')->references('id')->on('modules')->onDelete('cascade');
            $table->index(['id_hook', 'id_module'],'hook_module');
            $table->integer('position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('hooks_modules');
    }
}
