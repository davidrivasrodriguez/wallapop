<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['sale_id', 'path'];
    
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}