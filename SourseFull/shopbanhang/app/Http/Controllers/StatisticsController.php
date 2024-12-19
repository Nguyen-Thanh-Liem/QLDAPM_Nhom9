<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\Product;

class StatisticsController extends Controller
{
    public function index()
    {
        // Tổng số khách hàng
        $totalCustomers = Customer::count();

        // Số khách hàng mới theo tháng
        $monthlyNewCustomers = Customer::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Tổng số sản phẩm đã bán
        $totalProductsSold = Cart::sum('pty'); // Dùng cột 'pty' để tính tổng số sản phẩm đã bán

        // Tổng doanh thu
        $totalRevenue = Cart::selectRaw('SUM(pty * price) as total_revenue')
            ->first()->total_revenue;

        // Doanh thu theo tháng
        $monthlyRevenue = Cart::join('customers', 'carts.customer_id', '=', 'customers.id')
            ->selectRaw('YEAR(customers.created_at) as year, MONTH(customers.created_at) as month, SUM(carts.pty * carts.price) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Sản phẩm bán chạy nhất
        $topProducts = Cart::join('products', 'carts.product_id', '=', 'products.id')
            ->select('products.name', \DB::raw('SUM(carts.pty) as total_sold'))
            ->groupBy('carts.product_id', 'products.name')
            ->orderByDesc('total_sold')  // Sắp xếp theo tổng số sản phẩm bán được từ cao đến thấp
            ->get();


        // Lấy sản phẩm bán chạy nhất (phần tử đầu tiên trong danh sách)
        $topProduct = $topProducts->first();


        // Khách hàng mua hàng nhiều nhất với phân trang
        $topCustomers = Cart::join('customers', 'carts.customer_id', '=', 'customers.id')
            ->select('customers.name', 'customers.phone', \DB::raw('SUM(carts.pty) as total_purchased'))
            ->groupBy('carts.customer_id', 'customers.name', 'customers.phone')
            ->orderByDesc('total_purchased')  // Sắp xếp theo tổng số sản phẩm mua từ cao đến thấp
            ->get();

        // Truyền dữ liệu vào view
        return view('admin.statistics.index', compact(
            'totalCustomers',
            'monthlyNewCustomers',
            'totalProductsSold',
            'totalRevenue',
            'monthlyRevenue',
            'topProducts',
            'topProduct',
            'topCustomers'  // Thêm biến topCustomers vào compact

        ));
    }
}
