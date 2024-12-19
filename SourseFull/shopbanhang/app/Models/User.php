<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Một User có một Customer (quan hệ 1-1)
     */
    // Trong model User
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }


    /**
     * Các thuộc tính có thể gán giá trị hàng loạt
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Thêm role vào danh sách
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Các thuộc tính bị ẩn khi trả về mảng
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Các thuộc tính cần được cast sang kiểu dữ liệu khác
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
