<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\Users\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserService $user)
    {
        $this->user = $user;
    }

    public function create()
    {
        // view phải đúng thư mục lưu ý có s hay không.
        return view('admin.users.add', [
            'title' => 'Thêm User mới'
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password'   => 'required|min:6|confirmed',
            'role' => 'required|in:admin,user',  // Kiểm tra role
        ]);

        $this->user->insert($request);

        return redirect()->back();
    }

    public function index()
    {
        return view('admin.users.list', [
            'title' => 'Danh Sách User Mới Nhất',
            'users' => $this->user->get()
        ]);
        // trên có user có s dưới thì 0?
    }

    public function show(User $user)
    {
        return view('admin.users.edit', [
            'title' => 'Chỉnh Sửa User',
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|min:6|confirmed',
            'role' => 'required|in:admin,user',  // Kiểm tra role
        ]);

        $result = $this->user->update($request, $user);
        if ($result) {
            return redirect('/admin/users/list');
        }

        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $result = $this->user->destroy($request);
        if ($result) {
            return response()->json([
                'error' => false,
                'message' => 'Xóa thành công User'
            ]);
        }

        return response()->json(['error' => true]);
    }

    // Hiển thị form đổi mật khẩu
    public function showChangePasswordForm()
    {
        return view('admin.users.change-password', [
            'title' => 'Đổi Mật Khẩu'
        ]);
    }

    // Xử lý đổi mật khẩu
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        // Cập nhật mật khẩu mới
        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('status', 'Đổi mật khẩu thành công!');
    }
}
