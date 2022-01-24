<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryHasProduct extends Model
{
    use HasFactory;
    
    public $table = 'category_has_products';
}
