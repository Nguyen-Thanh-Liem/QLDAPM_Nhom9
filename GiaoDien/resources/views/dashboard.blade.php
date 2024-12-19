@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chào mừng, {{ auth()->user()->name }}!</h1>

    <!-- Nút Xem lịch sử đơn hàng -->
    <a href="{{ route('order.history') }}" class="btn btn-primary mt-3">Xem lịch sử đơn hàng</a>

    <!-- Nút Đăng xuất -->
    <form action="{{ route('logout') }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-danger">Đăng xuất</button>
    </form>
</div>
@endsection