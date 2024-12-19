<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Product\ProductService;
use App\Http\Services\Menu\MenuService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService, MenuService
    $menuService)
    {
        $this->productService = $productService;
        $this->menuService = $menuService;
    }
    public function index($id = '', $slug = '')
    {
        $product = $this->productService->show($id);
        $productsMore = $this->productService->more($id);
        $relatedProducts = $this->productService->getRelatedProducts($product->menu_id, $product->id);

        return view('products.content', [
            'title' => $product->name,
            'product' => $product,
            'menus' => $this->menuService->show(),
            'products' => $productsMore,
            'relatedProducts' => $relatedProducts,

        ]);
    }
}
