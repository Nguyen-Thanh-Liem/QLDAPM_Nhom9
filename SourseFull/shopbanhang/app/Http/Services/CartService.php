<?php

namespace App\Http\Services;

use App\Jobs\SendMail;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Discount;

use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Services\Menu\MenuService;


class CartService
{
    public function create($request)
    {
        //lấy thông tin số lượng sản phẩm và id sản phẩm
        $qty = (int)$request->input('num_product');
        $product_id = (int)$request->input('product_id');

        if ($qty <= 0 || $product_id <= 0) {
            Session::flash('error', 'Số lượng hoặc Sản phẩm không chính xác');
            return false;
        }

        //tạo session giỏ hàng để lưu sản phẩm --> xét 2 trường hợp
        //1. giỏ hàng carts chưa được tạo --> tạo giỏ hàng mới chứa mảng: id sản phẩm ==> số lượng
        //2. đã có giỏ hàng carts --> kiểm tra id sản phẩm có trong giỏ hàng chưa bằng Arr::exists
        //nếu có thì tăng số lượng cho id sản phẩm đang xét trong giỏ hàng
        //nếu chưa có thì thêm mới id sản phẩm vào giỏ hàng
        //lệnh Session::put là cập nhật lại giỏ hàng

        $carts = Session::get('carts');
        if (is_null($carts)) {
            Session::put('carts', [
                $product_id => $qty
            ]);
            return true;
        }

        //kiểm tra id sản phẩm có trong giỏ hàng chưa
        $exists = Arr::exists($carts, $product_id);
        if ($exists) {
            $carts[$product_id] = $carts[$product_id] + $qty;
            Session::put('carts', $carts);
            return true;
        }

        $carts[$product_id] = $qty;
        Session::put('carts', $carts);

        return true;
    }

    public function getProduct()
    {
        $carts = Session::get('carts');
        if (is_null($carts)) return [];

        $productId = array_keys($carts);
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->whereIn('id', $productId)
            ->get();
    }

    public function update($request)
    {
        Session::put('carts', $request->input('num_product'));

        return true;
    }

    public function remove($id)
    {
        $carts = Session::get('carts');
        unset($carts[$id]);

        Session::put('carts', $carts);
        return true;
    }

    public function addCart($request)
    {
        try {
            DB::beginTransaction();

            // Lấy thông tin người dùng đang đăng nhập
            $user = auth()->user();

            // Lấy thông tin session giỏ hàng
            $carts = Session::get('carts');
            if (is_null($carts)) {
                return false;
            }

            // Lấy thông tin mã giảm giá từ session
            $discount = Session::get('discount');

            // Lưu thông tin khách hàng vào table Customer
            $customer = Customer::create([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'email' => $request->input('email'),
                'content' => $request->input('content'),
                'discount_code' => $discount['code'] ?? null, // Lưu mã giảm giá nếu có
                'discount_value' => $discount['value'] ?? 0,  // Lưu giá trị giảm giá nếu có
                'user_id' => $user ? $user->id : null, // Lưu user_id nếu người dùng đã đăng nhập
            ]);

            // Gọi hàm infoProductCart để thêm sản phẩm trong giỏ vào table Carts
            $this->infoProductCart($carts, $customer->id, $user ? $user->id : null);

            DB::commit();
            Session::flash('success', 'Đặt Hàng Thành Công');

            // Tạo hàng đợi và thực hiện send mail
            SendMail::dispatch($request->input('email'))->delay(now()->addSeconds(2));

            // Hủy mã giảm giá
            Session::forget('discount');

            // Hủy giỏ hàng
            Session::forget('carts');
        } catch (\Exception $err) {
            DB::rollBack();
            \Log::error('Đặt hàng lỗi: ' . $err->getMessage());
            Session::flash('error', 'Đặt Hàng Lỗi, Vui lòng thử lại sau');
            return false;
        }

        return true;
    }



    // protected function infoProductCart($carts, $customer_id)
    // {
    //     //lấy danh sách tất cả sản phẩm trong giỏ hàng
    //     $productId = array_keys($carts);
    //     $products = Product::select('id', 'name', 'price', 'price_sale', 'thumb')
    //         ->where('active', 1)
    //         ->whereIn('id', $productId)
    //         ->get();

    //     //duyệt qua tất cả sản phẩm trong giỏ hàng rồi đưa vào mảng data
    //     $data = [];
    //     foreach ($products as $product) {
    //         $data[] = [
    //             'customer_id' => $customer_id,
    //             'product_id' => $product->id,
    //             'pty'   => $carts[$product->id],
    //             'price' => $product->price_sale != 0 ? $product->price_sale : $product->price
    //         ];
    //     }
    //     //đưa mảng data chứa danh sách hàng vào table Carts
    //     return Cart::insert($data);
    // }
    // Lấy danh sách tất cả sản phẩm trong giỏ hàng
    protected function infoProductCart($carts, $customer_id, $user_id = null)
    {
        // Lấy danh sách sản phẩm trong giỏ hàng
        $productId = array_keys($carts);
        $products = Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->whereIn('id', $productId)
            ->get();

        // Duyệt qua tất cả sản phẩm và lưu vào mảng
        $data = [];
        foreach ($products as $product) {
            // Lấy giá gốc hoặc giá sau giảm giá
            $finalPrice = $product->price_sale != 0 ? $product->price_sale : $product->price;

            $data[] = [
                'customer_id' => $customer_id,
                'product_id' => $product->id,
                'pty' => $carts[$product->id],
                'price' => $finalPrice, // Lưu giá sau khi giảm giá
                'user_id' => $user_id,  // Lưu user_id nếu có
            ];
        }

        // Đưa vào bảng Cart
        return Cart::insert($data);
    }



    public function getUserOrderHistory($userId)
    {
        // Lấy danh sách các đơn hàng đã đặt hàng bởi user
        return Cart::where('user_id', $userId)
            ->with(['product' => function ($query) {
                $query->select('id', 'name', 'thumb');
            }])
            ->orderByDesc('created_at')
            ->get();
    }

    public function getCustomer()
    {
        return Customer::orderByDesc('id')->paginate(15);
    }

    public function getProductForCart($customer)
    {
        return $customer->carts()->with(['product' => function ($query) {
            $query->select('id', 'name', 'thumb');
        }])->get();
    }
    // thêm
    public function applyDiscount($request)
    {
        $code = $request->input('discount_code');
        $discount = Discount::where('code', $code)
            ->where('active', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$discount || $discount->quantity <= 0) {
            Session::flash('error', 'Mã giảm giá không hợp lệ hoặc đã hết số lượng.');
            return false;
        }

        // Lấy giỏ hàng
        $carts = Session::get('carts');
        if (is_null($carts)) {
            Session::flash('error', 'Giỏ hàng trống.');
            return false;
        }

        // Tính tổng giá trị
        $products = $this->getProduct();
        $total = 0;
        foreach ($products as $product) {
            $price = $product->price_sale != 0 ? $product->price_sale : $product->price;
            $total += $price * $carts[$product->id];
        }

        // Tính giảm giá
        $discountValue = ($total * $discount->percentage) / 100;

        // Cập nhật session
        Session::put('discount', [
            'code' => $code,
            'value' => $discountValue,
        ]);

        // Giảm số lượng mã giảm giá
        $discount->decrement('quantity');

        Session::flash('success', 'Áp dụng mã giảm giá thành công.');
        return true;
    }

    // Trong CartService.php
    public function getUserOrders($userId)
    {
        // Lấy danh sách các đơn hàng của người dùng từ bảng Cart và Customer
        return Cart::where('customer_id', $userId)->with('product')->get();
    }
}
