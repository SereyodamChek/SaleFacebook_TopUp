<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $fillable = [
    'menu_item_id',
    'title',
    'price',
    'stock',
    'sold_out_amount',
    'description',
    'is_active',
  ];

  public function menuItem()
  {
    return $this->belongsTo(MenuItem::class);
  }
}
