@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lịch sử đơn hàng</h2>

    @if ($orders->isEmpty())
    <p>Bạn chưa có đơn hàng nào.</p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tổng cộng</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->product->name ?? 'N/A' }}</td>
                <td>{{ $order->pty }}</td>
                <td>{{ number_format($order->price) }} VND</td>
                <td>{{ number_format($order->price * $order->pty) }} VND</td>
                <td>{{ $order->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection