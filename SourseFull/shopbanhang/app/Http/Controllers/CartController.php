<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Services\CartService;
use Illuminate\Support\Facades\Session;
use App\Http\Services\Menu\MenuService;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Menu;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService, MenuService $menuService)
    {
        $this->cartService = $cartService;
        $this->menuService = $menuService;
    }

    public function index(Request $request)
    {
        $result = $this->cartService->create($request);
        if ($result === false) {
            return redirect()->back();
        }

        return redirect('/carts');
    }

    public function show()
    {
        $products = $this->cartService->getProduct();
        return view('carts.list', [
            'title' => 'Giỏ Hàng',
            'products' => $products,
            'menus' => $this->menuService->show(),
            // tại sao có menus? ko có thì lỗi
            'carts' => Session::get('carts')
        ]);
    }

    public function update(Request $request)
    {
        $this->cartService->update($request);

        return redirect('/carts');
    }

    public function remove($id = 0)
    {
        $this->cartService->remove($id);

        return redirect('/carts');
    }

    public function addCart(Request $request)
    {
        $this->cartService->addCart($request);

        return redirect()->back();
    }
    public function applyDiscount(Request $request)
    {
        $result = $this->cartService->applyDiscount($request);

        return redirect('/carts');
    }

    public function history()
    {
        // Lấy thông tin người dùng đã đăng nhập
        $user = auth()->user(); // Lấy user đã đăng nhập thông qua auth

        // Lấy danh sách các đơn hàng của người dùng
        $orders = $this->cartService->getUserOrders($user->id);

        return view('carts.history', [
            'orders' => $orders
        ]);
    }
}
