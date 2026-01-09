<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
  protected $fillable = [
    'menu_category_id','title','url','icon','status','status_type','sort','is_active'
  ];

  public function category()
  {
    return $this->belongsTo(MenuCategory::class);
  }
  public function products()
  {
    return $this->hasMany(Product::class);
  }
}
