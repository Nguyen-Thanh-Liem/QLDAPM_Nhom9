<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
        'content',
        'discount_code',
        'discount_value',
        'user_id'
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class, 'customer_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Bạn có thể sử dụng hàm boot() để tự động gán user_id khi tạo khách hàng
    // Trong model Customer
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (Auth::check()) {
                $customer->user_id = Auth::id(); // Gán user_id từ người dùng hiện tại
            }
        });
    }
}
