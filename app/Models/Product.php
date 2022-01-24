<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;
use App\Models\CategoryHasProduct;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    
    public $appends = ['imagePath'];
    
    public $fillable = [
        'name',
        'description',
        'price',
        'image',
    ];
    
    public $hidden = [
        'pivot',
    ];
    
    public function getImagePathAttribute() {
        return $this->image ? Product::getRelativeImagePath($this->image).'/'.$this->image : null;
    }
    
    public static function getRelativeImagePath($fileName) {
        return '/uploads/products/'.substr($fileName, 0, 6);
    }
    
    public function categories() {
        return $this->belongsToMany(Category::class, 'category_has_products', 'product_id', 'category_id');
    }
    
    public function categoryHasProducts() {
        return $this->hasMany(CategoryHasProduct::class, 'product_id');
    }
}
