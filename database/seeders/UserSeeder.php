<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name'=>'山本太郎',
            'email'=>'user@user.com',
            'password'=>Hash::make('password123'),
            'created_at'=>'2024/04/11 13:00:00'
        ]);
    }
}
