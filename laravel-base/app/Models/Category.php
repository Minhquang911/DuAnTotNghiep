
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'is_active', 'description'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
} 
