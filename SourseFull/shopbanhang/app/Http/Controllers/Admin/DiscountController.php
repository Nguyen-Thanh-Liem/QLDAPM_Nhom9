<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Discount\DiscountAdminService;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountAdminService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function index()
    {
        return view('admin.discount.list', [
            'title' => 'Danh Sách Mã Giảm Giá',
            'discounts' => $this->discountService->getAll()
        ]);
    }

    public function create()
    {
        return view('admin.discount.add', [
            'title' => 'Thêm Mã Giảm Giá'
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|unique:discounts,code',
            'percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'active' => 'required|boolean',
            'quantity' => 'required|integer|min:0',  // Thêm validation cho quantity
        ]);

        $this->discountService->create($request);
        return redirect()->back();
    }


    public function edit(Discount $discount)
    {
        return view('admin.discount.edit', [
            'title' => 'Chỉnh Sửa Mã Giảm Giá',
            'discount' => $discount
        ]);
    }

    public function update(Request $request, Discount $discount)
    {
        $this->validate($request, [
            'code' => 'required|unique:discounts,code,' . $discount->id,
            'percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'active' => 'required|boolean',
            'quantity' => 'required|integer|min:0',  // Thêm validation cho quantity
        ]);

        $this->discountService->update($request, $discount);
        return redirect('/admin/discounts/list');
    }

    public function destroy(Request $request)
    {
        $result = $this->discountService->delete($request);
        if ($result) {
            return response()->json([
                'error' => false,
                'message' => 'Xóa thành công mã giảm giá'
            ]);
        }

        return response()->json(['error' => true]);
    }
}
