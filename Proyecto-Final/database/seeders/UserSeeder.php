<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Manuel',
                'email' => 'manuamayaorozco@gmail.com',
                'password' => '$2y$12$0LByH4ogksH5Q5R9tamPh.AsMCcsT6PvYWPyF7YMQjygsI50CLAvu',
                'banned' => false,
                'photo' => 'profiles/mXoq448JNy52HObWQYg47YBWvshlYFk70zXtk2SC.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
