@extends('admin.main')

@section('content')
<form action="" method="POST">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="menu">Tên Đăng Nhập</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="menu">Email</label>
                    <input type="text" name="email" value="{{ old('email') }}" class="form-control">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="menu">Password</label>
                <input type="text" name="password" value="{{ old('password') }}" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="password_confirmation">Xác nhận Mật khẩu</label>
                <input type="password" name="password_confirmation" value="" class="form-control">
                <small class="form-text text-muted">Nếu không thay đổi mật khẩu, để trống trường này.</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="role">Quyền</label>
                <select name="role" class="form-control" required>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
        </div>
    </div>
    </div>
    </div>








    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Thêm Users</button>
    </div>
    @csrf
</form>
@endsection