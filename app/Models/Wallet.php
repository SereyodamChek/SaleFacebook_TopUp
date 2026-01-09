<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id','balance','total_deposit','used_balance','discount_percent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
