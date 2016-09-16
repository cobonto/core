<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            DB::table('hooks')->insert([
                    ['name' =>'moduleAdminRoutes'],
                    ['name' =>'moduleRoutes'],
                    ['name' =>'displayAdminSideBarTop'],
                    ['name' =>'displayAdminSideBar'],
                ]
            );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
