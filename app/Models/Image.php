<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'filename',
    ];

    public function owner()
    {
        // リレーション1対多
        return $this->belongsTo(Owner::class);
    }
}
