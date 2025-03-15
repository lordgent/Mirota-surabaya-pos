<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'category_id', 'stock', 'product_code','photo','user_id'];

      public function category()
      {
          return $this->belongsTo(Category::class);
      }
      public function user()
      {
          return $this->belongsTo(User::class);
      }
      public function orders()
      {
          return $this->hasMany(Order::class);
      }
  
}
