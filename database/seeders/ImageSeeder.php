<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('shops')->insert([
            [
                'owner_id' => 1,
                'filename'=>'sample1.jpg',
                'title'=>'鉛筆'
            ],
            [
                'owner_id' => 1,
                'filename'=>'sample2.jpg',
                'title'=>'クレヨン'
            ],
            [
                'owner_id' => 1,
                'filename'=>'sample3.jpg',
                'title'=>'ハサミ'
            ],
            [
                'owner_id' => 1,
                'filename'=>'sample4.jpg',
                'title'=>'カッター'
            ],
            [
                'owner_id' => 1,
                'filename'=>'sample5.jpg',
                'title'=>'ノート'
            ],
            [
                'owner_id' => 1,
                'filename'=>'sample6.jpg',
                'title'=>'ボールペン'
            ],
        ]);
    }
}
