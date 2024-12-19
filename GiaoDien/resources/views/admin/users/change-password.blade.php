@extends('admin.main')

@section('content')
<h2>Đổi Mật Khẩu</h2>

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('user.change-password.post') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="current_password">Mật khẩu hiện tại</label>
        <input type="password" class="form-control" id="current_password" name="current_password" required>
    </div>

    <div class="form-group">
        <label for="new_password">Mật khẩu mới</label>
        <input type="password" class="form-control" id="new_password" name="new_password" required>
    </div>

    <div class="form-group">
        <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
    </div>

    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
</form>
@endsection