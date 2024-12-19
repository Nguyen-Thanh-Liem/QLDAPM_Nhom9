<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts'; // Tên bảng
    protected $fillable = [
        'code',
        'percentage',
        'start_date',
        'end_date',
        'active',
        'quantity'
    ];
}
