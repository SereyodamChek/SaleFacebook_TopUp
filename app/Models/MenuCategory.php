<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
  protected $fillable = ['group_key','title','sort','is_active'];

  public function items()
  {
    return $this->hasMany(MenuItem::class)->orderBy('sort');
  }
}
