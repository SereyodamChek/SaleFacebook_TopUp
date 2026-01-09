<?php

// app/Models/Topup.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
  protected $fillable = [
    'user_id','amount','currency','status','qr','md5','verify_payload','paid_at'
  ];

  protected $casts = [
    'verify_payload' => 'array',
    'paid_at' => 'datetime',
  ];

  public function user() {
    return $this->belongsTo(User::class);
  }
}
