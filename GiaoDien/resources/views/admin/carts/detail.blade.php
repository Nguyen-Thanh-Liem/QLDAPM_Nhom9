@extends('admin.main')

@section('content')
<div class="customer mt-3">
    <ul>
        <li>Tên khách hàng: <strong>{{ $customer->name }}</strong></li>
        <li>Số điện thoại: <strong>{{ $customer->phone }}</strong></li>
        <li>Địa chỉ: <strong>{{ $customer->address }}</strong></li>
        <li>Email: <strong>{{ $customer->email }}</strong></li>
        <li>Ghi chú: <strong>{{ $customer->content }}</strong></li>
        <li>Mã giảm giá: <strong>{{ $discountCode ?? 'Không có' }}</strong></li>
        <li>Giá trị giảm giá: <strong>{{ $discountValue > 0 ? number_format($discountValue, 0, '', '.') : 'Không có' }}</strong></li>
    </ul>
</div>

<div class="carts">
    <table class="table">
        <tbody>
            <tr class="table_head">
                <th class="column-1">IMG</th>
                <th class="column-2">Product</th>
                <th class="column-3">Price</th>
                <th class="column-4">Quantity</th>
                <th class="column-5">Total</th>
            </tr>

            @foreach($carts as $key => $cart)
            @php
            $price = $cart->price * $cart->pty;
            @endphp
            <tr>
                <td class="column-1">
                    <div class="how-itemcart1">
                        <img src="{{ $cart->product->thumb }}" alt="IMG" style="width: 100px">
                    </div>
                </td>
                <td class="column-2">{{ $cart->product->name }}</td>
                <td class="column-3">{{ number_format($cart->price, 0, '', '.') }}</td>
                <td class="column-4">{{ $cart->pty }}</td>
                <td class="column-5">{{ number_format($price, 0, '', '.') }}</td>
            </tr>
            @endforeach

            <tr>
                <td colspan="4" class="text-right">Tổng Tiền</td>
                <td>{{ number_format($total, 0, '', '.') }}</td>
            </tr>

            <!-- Hiển thị tổng tiền giảm giá nếu có -->
            @if ($discountTotal > 0)
            <tr>
                <td colspan="4" class="text-right">Giảm Giá</td>
                <td>{{ number_format($discountTotal, 0, '', '.') }}</td>
            </tr>
            @endif

            <!-- Hiển thị tổng tiền sau khi giảm -->
            <tr>
                <td colspan="4" class="text-right"><strong>Tổng Tiền Sau Giảm</strong></td>
                <td><strong>{{ number_format($totalAfterDiscount, 0, '', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="mt-3">
    <a href="{{ route('admin.carts.exportPdf', $customer->id) }}" class="btn btn-primary">
        In Đơn Hàng PDF
    </a>
</div>

<div class="order-status mt-3">
    <p><strong>Trạng thái đơn hàng:</strong> {{ $status }}</p>

    <!-- Form cập nhật trạng thái -->
    <form action="{{ route('admin.carts.updateStatus', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="status">Cập nhật trạng thái đơn hàng:</label>
        <select name="status" id="status" class="form-control">
            <option value="Đã Đặt Hàng" {{ $status == 'Đã Đặt Hàng' ? 'selected' : '' }}>Đã Đặt Hàng</option>
            <option value="Đang Giao Hàng" {{ $status == 'Đang Giao Hàng' ? 'selected' : '' }}>Đang Giao Hàng</option>
            <option value="Giao Hàng Thành Công" {{ $status == 'Giao Hàng Thành Công' ? 'selected' : '' }}>Giao Hàng Thành Công</option>
        </select>
        <button type="submit" class="btn btn-success mt-2">Cập nhật trạng thái</button>
    </form>
</div>

@endsection