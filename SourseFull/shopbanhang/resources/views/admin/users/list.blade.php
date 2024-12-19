@extends('admin.main')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th style="width: 50px">ID</th>
            <th>Tên Đăng Nhập</th>
            <th>Mail</th>
            <th>Mật khẩu</th>
            <th>Quyền</th> <!-- Thêm cột quyền -->
            <th style="width: 100px">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $key => $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->password }}</td>
            <td>{{ ucfirst($user->role) }}</td> <!-- Hiển thị quyền, với ucfirst để in hoa chữ cái đầu tiên -->

            <td>
                <a class="btn btn-primary btn-sm" href="/admin/users/edit/{{ $user->id }}">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="#" class="btn btn-danger btn-sm"
                    onclick="removeRow({{ $user->id }}, '/admin/users/destroy')">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="card-footer clearfix">
    {!! $users->links() !!}
</div>
@endsection