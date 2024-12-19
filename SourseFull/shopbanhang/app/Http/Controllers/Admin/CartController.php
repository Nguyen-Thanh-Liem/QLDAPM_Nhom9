<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Services\CartService;
use App\Http\Services\Menu\MenuService;

class CartController extends Controller
{
    protected $cart;
    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        return view('admin.carts.customer', [
            'title' => 'Danh Sách Đơn Đặt Hàng',
            'customers' => $this->cart->getCustomer()
        ]);
    }


    public function show(Customer $customer)
    {
        // Lấy các sản phẩm trong giỏ hàng của khách hàng
        $carts = $this->cart->getProductForCart($customer);

        // Lấy trạng thái đơn hàng từ cột 'status' của bảng 'carts'
        $status = $carts->first()->status ?? 'Đã Đặt Hàng';

        // Lấy mã giảm giá và giá trị giảm từ customer
        $discountCode = $customer->discount_code;
        $discountValue = $customer->discount_value; // Giá trị giảm giá

        // Tính toán tổng tiền
        $total = 0;

        foreach ($carts as $cart) {
            $price = $cart->price * $cart->pty;
            $total += $price;
        }

        // Tính toán giá trị giảm giá: nếu giá trị giảm giá là số tuyệt đối
        $discountTotal = 0;
        if ($discountValue > 0) {
            // Nếu discountValue là giá trị tuyệt đối (không phải phần trăm), thì giảm trực tiếp
            $discountTotal = $discountValue; // Giảm trực tiếp theo giá trị tuyệt đối
        }

        // Tổng tiền sau khi giảm
        $totalAfterDiscount = $total - $discountTotal;

        return view('admin.carts.detail', [
            'title' => 'Chi Tiết Đơn Hàng: ' . $customer->name,
            'customer' => $customer,
            'carts' => $carts,
            'status' => $status,
            'total' => $total,
            'discountTotal' => $discountTotal,
            'totalAfterDiscount' => $totalAfterDiscount,
            'discountCode' => $discountCode,
            'discountValue' => $discountValue,
        ]);
    }

    public function updateStatus(Request $request, Customer $customer)
    {
        $status = $request->input('status');

        // Cập nhật trạng thái cho tất cả các sản phẩm của khách hàng trong bảng 'carts'
        Cart::where('customer_id', $customer->id)->update(['status' => $status]);

        return back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }

    // hiển thị chi tiết đơn hàng tíh tổng tiền

    public function exportPdf(Customer $customer)
    {
        // Lấy dữ liệu giống phương thức `show`
        $carts = $this->cart->getProductForCart($customer);
        $status = $carts->first()->status ?? 'Đã Đặt Hàng';
        $discountCode = $customer->discount_code;
        $discountValue = $customer->discount_value;

        $total = 0;
        foreach ($carts as $cart) {
            $price = $cart->price * $cart->pty;
            $total += $price;
        }

        $discountTotal = $discountValue > 0 ? $discountValue : 0;
        $totalAfterDiscount = $total - $discountTotal;

        // Chuẩn bị dữ liệu cho PDF
        $data = [
            'customer' => $customer,
            'carts' => $carts,
            'status' => $status,
            'total' => $total,
            'discountTotal' => $discountTotal,
            'totalAfterDiscount' => $totalAfterDiscount,
            'discountCode' => $discountCode,
            'discountValue' => $discountValue,
        ];

        // Tạo file PDF từ view
        $pdf = Pdf::loadView('admin.carts.pdf', $data);

        // Trả về file PDF tải xuống
        return $pdf->download('ChiTietDonHang_' . $customer->name . '.pdf');
    }
}
