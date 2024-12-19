<?php

namespace App\Http\Controllers;

use App\Http\Services\CartService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function history()
    {
        $userId = Auth::id(); // Lấy user_id của người dùng hiện tại
        $orders = $this->cartService->getUserOrderHistory($userId);

        return view('orders.history', compact('orders')); // Trả về view hiển thị lịch sử
    }
}
