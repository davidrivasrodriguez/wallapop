<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'product',
        'description',
        'price',
        'isSold',
        'thumbnail'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}