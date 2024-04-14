<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('primary_categories')->insert([
            [
                'name' => '文房具・事務用品',
                'sort_order'=> 1,
            ],
            [
                'name' => '日用消耗品',
                'sort_order'=> 2,
            ],
            [
                'name' => '手芸・クラフト・生地',
                'sort_order'=> 3,
            ],
        ]);

        DB::table('secondary_categories')->insert([
            [
                'name' => '筆記具',
                'sort_order'=> 1,
                'primary_category_id'=> 1,
            ],
            [
                'name' => '手帳・ノート・紙製品',
                'sort_order'=> 2,
                'primary_category_id'=> 1,
            ],
            [
                'name' => 'ファイル・バインダー',
                'sort_order'=> 3,
                'primary_category_id'=> 1,
            ],
            [
                'name' => '洗剤・柔軟剤・クリーナー',
                'sort_order'=> 4,
                'primary_category_id'=> 2,
            ],
            [
                'name' => 'ティッシュ・トイレットペーパー',
                'sort_order'=> 5,
                'primary_category_id'=> 2,
            ],
            [
                'name' => '消臭剤・芳香剤',
                'sort_order'=> 6,
                'primary_category_id'=> 2,
            ],
            [
                'name' => '生地・布',
                'sort_order'=> 7,
                'primary_category_id'=> 3,
            ],
            [
                'name' => '裁縫材料',
                'sort_order'=> 8,
                'primary_category_id'=> 3,
            ],
            [
                'name' => '毛糸',
                'sort_order'=> 9,
                'primary_category_id'=> 3,
            ],
        ]);
    }
}
