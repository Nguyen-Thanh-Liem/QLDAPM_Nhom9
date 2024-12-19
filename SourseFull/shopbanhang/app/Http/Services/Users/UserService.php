<?php

namespace App\Http\Services\Users;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function insert($request)
    {
        try {
            // Lấy các trường hợp lệ từ request
            $data = $request->only(['name', 'email', 'password', 'role']);
            $data['password'] = bcrypt($data['password']); // Mã hóa mật khẩu

            User::create($data);

            Session::flash('success', 'Thêm User mới thành công');
        } catch (\Exception $err) {
            Session::flash('error', 'Thêm User LỖI');
            Log::error($err->getMessage());

            return false;
        }

        return true;
    }

    public function get()
    {
        // Phân trang danh sách người dùng
        return User::orderByDesc('id')->paginate(15);
    }

    public function update($request, $user)
    {
        try {
            // Lấy dữ liệu cần cập nhật
            $data = $request->only(['name', 'email', 'role']);

            // Nếu mật khẩu được nhập, mã hóa và cập nhật
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $user->update($data);

            Session::flash('success', 'Cập nhật User thành công');
        } catch (\Exception $err) {
            Session::flash('error', 'Cập nhật User Lỗi');
            Log::error($err->getMessage());

            return false;
        }

        return true;
    }

    public function destroy($request)
    {
        try {
            $user = User::find($request->input('id'));
            if ($user) {
                // Xóa hình ảnh (nếu có)
                if ($user->thumb) {
                    $path = str_replace('storage', 'public', $user->thumb);
                    Storage::delete($path);
                }

                $user->delete();

                Session::flash('success', 'Xóa User thành công');
                return true;
            }

            Session::flash('error', 'User không tồn tại');
        } catch (\Exception $err) {
            Session::flash('error', 'Xóa User Lỗi');
            Log::error($err->getMessage());
        }

        return false;
    }

    public function show()
    {
        // Hiển thị danh sách người dùng đang hoạt động
        return User::where('active', 1)->orderByDesc('sort_by')->get();
    }
}
