<?php

namespace App\Models;

use App\Http\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 認証機能を付ける
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Shop;
use App\Models\Image;

// Authenticatableを継承させる
class Owner extends Authenticatable
{
    // SoftDeletesを付けることで、OwnerモデルのデリートはSoftDeletesで扱われる。
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function shop()
    {
        // リレーション1対1
        return $this->hasOne(Shop::class);
    }

    public function image()
    {
        // リレーション1対多
        return $this->hasMany(Image::class);
    }
}
