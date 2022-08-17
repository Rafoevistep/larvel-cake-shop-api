<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'is_paid',
        'payment_method',
        'flat',
        'street_name',
        'area',
        'landmark',
        'city',
    ];


    public function products(){

        return $this->belongsToMany('App\Models\Product');
    }
}
