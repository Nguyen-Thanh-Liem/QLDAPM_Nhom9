<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'product_id',
        'pty',
        'price'
    ];

    public function product()
    {
        // return $this->hasOne(Product::class, 'id', 'product_id');
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
