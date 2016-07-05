<?php

use Illuminate\Database\Seeder;

class HookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hooks')->insert([
                ['name' =>'displayAdminHeader'],
                ['name' =>'displayAdminFooter'],
                ['name' =>'displayAdminNav'],
                ['name' =>'displayDashboardTop'],
                ['name' =>'displayDashboardLeft'],
                ['name' =>'displayDashboardRight'],
                ['name' =>'displayDashboardFooter'],
            ]
        );
    }
}
