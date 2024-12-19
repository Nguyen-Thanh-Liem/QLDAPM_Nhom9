<?php

namespace App\Http\Services\Discount;

use App\Models\Discount;
use Illuminate\Support\Facades\Session;

class DiscountAdminService
{
    public function getAll()
    {
        return Discount::orderByDesc('id')->paginate(10);
    }

    public function create($request)
    {
        try {
            Discount::create($request->only('code', 'percentage', 'start_date', 'end_date', 'active', 'quantity'));
            Session::flash('success', 'Thêm mã giảm giá thành công');
        } catch (\Exception $err) {
            Session::flash('error', 'Thêm mã giảm giá thất bại');
            return false;
        }
        return true;
    }


    public function update($request, $discount)
    {
        try {
            $discount->fill($request->only('code', 'percentage', 'start_date', 'end_date', 'active', 'quantity'));
            $discount->save();
            Session::flash('success', 'Cập nhật mã giảm giá thành công');
        } catch (\Exception $err) {
            Session::flash('error', 'Cập nhật mã giảm giá thất bại');
            return false;
        }
        return true;
    }


    public function delete($request)
    {
        $discount = Discount::where('id', $request->input('id'))->first();
        if ($discount) {
            $discount->delete();
            return true;
        }
        return false;
    }
}
