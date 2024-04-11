<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->insert([
            'name'=>'山本太郎',
            'email'=>'test@gmail.com',
            'password'=>Hash::make('password123'),
            'created_at'=>'2024/04/11 13:00:00'
        ]);
    }
}
