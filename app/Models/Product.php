<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image;

class Product extends Model
{
    use HasFactory;

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        // 第2引数に外部キー、第3引数で親モデル名を設定
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    // テーブル名にimage1があるため、imageFirstに変更
    public function imageFirst()
    {
        // 第2引数にimage1のようにidを付けない場合、判断できないため、第3引数でidに姫づくことを書く
        return $this->belongsTo(Image::class, 'image1', 'id');
    }
}
