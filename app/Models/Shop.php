<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;
use App\Models\Product;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'information',
        'filename',
        'is_selling'
    ];

    public function owner()
    {
        // リレーション1対1
        return $this->belongsTo(Owner::class);
    }

    public function product()
    {
        // リレーション1対多
        return $this->hasMny(Product::class);
    }
}
