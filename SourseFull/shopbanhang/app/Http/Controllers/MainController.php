<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Slider\SliderService;
use App\Http\Services\Menu\MenuService;
use App\Http\Services\Product\ProductService;

class MainController extends Controller
{
    protected $slider;
    protected $menu;
    protected $product;

    // Sử dụng dependency injection để inject các service vào Controller
    public function __construct(SliderService $slider, MenuService $menu, ProductService $product)
    {
        $this->slider = $slider;
        $this->menu = $menu;
        $this->product = $product;
    }

    // Hàm xử lý logic khi vào trang chủ
    public function index()
    {
        // Truyền dữ liệu vào view
        return view('home', [
            'title' => 'Shop Thời Trang',
            'sliders' => $this->slider->show(), // Lấy sliders từ service
            'menus' => $this->menu->show(), // Lấy menus từ service
            'products' => $this->product->get() // Lấy products từ service
        ]);
    }

    public function loadProduct(Request $request)
    {
        $page = $request->input('page', 0);
        $result = $this->product->get($page);
        if (count($result) != 0) {
            $html = view('products.list', ['products' => $result])->render();

            return response()->json(['html' => $html]);
        }

        return response()->json(['html' => '']);
    }
}
