<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;
    protected $table='categories';
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $fillable = [
        'name',
        'icon',
        'user_id',
    ];
}
