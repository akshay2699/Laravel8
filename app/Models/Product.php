<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Product;

class Product extends Model
{
    use HasFactory;
    protected $table='products';
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
    ];
}
