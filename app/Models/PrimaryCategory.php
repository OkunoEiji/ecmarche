<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SecondaryCategory;

class PrimaryCategory extends Model
{
    use HasFactory;

    // リレーション1対多
    public function secondary()
    {
        return $this->hasMany(SecondaryCategory::class);
    }
}
