<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'user_id',
        'code',
    ];

    protected function casts(): array
    {
        return [
            'code' => 'string',
        ];
    }
}
