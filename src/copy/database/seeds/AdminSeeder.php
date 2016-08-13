<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
                [
                    'name'=>'Admin',
                    'email' =>'demo@cobonto.com',
                    'active' =>'1',
                    'password' =>Hash::make('demodemo'),
                    'is_admin' =>'1',
                    'created_at' =>date('YYYY-MM-DD H:i:s'),
                    'updated_at' =>date('YYYY-MM-DD H:i:s'),
                ],
            ]
        );
    }
}
