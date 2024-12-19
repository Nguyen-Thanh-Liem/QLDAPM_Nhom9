<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Đơn Hàng</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h1>Chi Tiết Đơn Hàng</h1>
    <h2>SHOP NƯỚC HOA</h2>
    <p><strong>Tên khách hàng:</strong> {{ $customer->name }}</p>
    <p><strong>Số điện thoại:</strong> {{ $customer->phone }}</p>
    <p><strong>Địa chỉ:</strong> {{ $customer->address }}</p>
    <p><strong>Email:</strong> {{ $customer->email }}</p>
    <p><strong>Ghi chú:</strong> {{ $customer->content }}</p>

    <table>
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carts as $cart)
            <tr>
                <td><img src="{{ $cart->product->thumb }}" alt="IMG" style="width: 50px;"></td>
                <td>{{ $cart->product->name }}</td>
                <td>{{ number_format($cart->price, 0, '', '.') }}</td>
                <td>{{ $cart->pty }}</td>
                <td>{{ number_format($cart->price * $cart->pty, 0, '', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right">Tổng Tiền</td>
                <td>{{ number_format($total, 0, '', '.') }}</td>
            </tr>
            @if ($discountTotal > 0)
            <tr>
                <td colspan="4" class="text-right">Giảm Giá</td>
                <td>{{ number_format($discountTotal, 0, '', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="4" class="text-right"><strong>Tổng Tiền Sau Giảm</strong></td>
                <td><strong>{{ number_format($totalAfterDiscount, 0, '', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>

</html>