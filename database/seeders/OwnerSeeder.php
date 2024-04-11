<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('owners')->insert([
            [
                'name'=>'owner1',
                'email'=>'test1@test.com',
                'password'=>Hash::make('password123'),
                'created_at'=>'2024/04/11 13:00:00'
            ],
            [
                'name'=>'owner2',
                'email'=>'test2@test.com',
                'password'=>Hash::make('password123'),
                'created_at'=>'2024/04/11 13:00:00'
            ],
            [
                'name'=>'owner3',
                'email'=>'test3@test.com',
                'password'=>Hash::make('password123'),
                'created_at'=>'2024/04/11 13:00:00'
            ],
        ]);
    }
}
