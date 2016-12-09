<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Roles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('roles', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->unique();
            $table->boolean('admin')->default(1);
        });

        Schema::create('permissions', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->unique();
        });

        Schema::create('roles_permissions', function (Blueprint $table)
        {
            $table->integer('id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['id', 'role_id']);
        });
        // add role_id to users
        // add data to roles
        \DB::table('roles')->insert([
            [
                'name' => 'admin',
                'admin' => 1
            ],
            [
                'name' => 'customer',
                'admin' => 0,
            ]
        ]);
        Schema::table('users', function (Blueprint $table)
        {
            $table->integer('role_id')->unsigned()->default(1);
            $table->foreign('role_id')
                ->references('id')
                ->on('roles');
        });
        Schema::table('admins', function (Blueprint $table)
        {
            $table->integer('role_id')->unsigned()->default(1);
            $table->foreign('role_id')
                ->references('id')
                ->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles');
        Schema::drop('permissions');
        Schema::drop('role_has_permissions');
        Schema::drop('roles');
        Schema::drop('permissions');
    }
}
