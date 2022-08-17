<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable =[
        'name',
        'description',
        'price',
        'image',
        'category_id'
    ];

    public function categories()
    {
        return $this->AsOne('App\Models\Category');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function availableProduct(): HasOne
    {
        return $this->hasOne(AvailableProduct::class, 'product_id');
    }
}
