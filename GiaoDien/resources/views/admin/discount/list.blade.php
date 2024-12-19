@extends('admin.main')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã</th>
            <th>Phần Trăm</th>
            <th>Số Lượng</th> <!-- Thêm cột số lượng -->
            <th>Ngày Bắt Đầu</th>
            <th>Ngày Kết Thúc</th>
            <th>Kích Hoạt</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($discounts as $discount)
        <tr>
            <td>{{ $discount->id }}</td>
            <td>{{ $discount->code }}</td>
            <td>{{ $discount->percentage }}%</td>
            <td>{{ $discount->quantity }}</td> <!-- Hiển thị số lượng -->
            <td>{{ $discount->start_date }}</td>
            <td>{{ $discount->end_date }}</td>
            <td>{!! \App\Helpers\Helper::active($discount->active) !!}</td>
            <td>
                <a href="/admin/discounts/edit/{{ $discount->id }}" class="btn btn-primary btn-sm">Sửa</a>
                <a href="#" class="btn btn-danger btn-sm"
                    onclick="removeRow({{ $discount->id }}, '/admin/discounts/destroy')">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="card-footer clearfix">
    {!! $discounts->links() !!}
</div>
@endsection