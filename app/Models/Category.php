<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    public $fillable = [
        'name',
        'products_count',
    ];
    
    public $hidden = [
        'pivot',
    ];
    
    public function products() {
        return $this->belongsToMany(Product::class, 'category_has_products', 'category_id', 'product_id');
    }
    
    public static function updateCounters($categoryIds) {
        $categories = self::whereIn('id', $categoryIds)->get();
        foreach($categories as $category) {
            $category->products_count = $category->products()->count();
            $category->save();
        }
    }
}
