<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Lọc theo keyword (full_name hoặc email)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Sắp xếp theo id
        $sortOrder = $request->get('sort', 'asc');
        $users = $query->orderBy('id', $sortOrder)->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }


    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'email' => 'nullable|email|unique:users,email',
            'full_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|digits:10',
            'role' => 'required|in:customer,admin',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'username.required' => 'Tên đăng nhập không được bỏ trống.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'password.required' => 'Mật khẩu không được bỏ trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'full_name.required' => 'Họ và tên không được bỏ trống.',
            'full_name.max' => 'Họ và tên không được dài quá 255 ký tự.',
            'address.max' => 'Địa chỉ không được dài quá 255 ký tự.',
            'phone.digits' => 'Số điện thoại phải đúng 10 chữ số và không được chứa ký tự khác.',
            'role.required' => 'Vai trò không được bỏ trống.',
            'role.in' => 'Vai trò không hợp lệ.',
            'image.image' => 'File phải là ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        $user = new User($request->except('password', 'password_confirmation', 'image'));
        $user->password = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('assets/images');
            
            // Tạo thư mục nếu chưa tồn tại (nếu cần)
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);

            $user->image = 'assets/images/' . $filename;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User created successfully!');
    }


    public function edit(User $user)
    {
        //dd($user->id, $user->hash_id);
        
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => ['required', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6|confirmed',
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'full_name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable|digits:10',
            'role' => 'required|in:customer,admin',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'username.required' => 'Tên đăng nhập không được bỏ trống.',
                'username.unique' => 'Tên đăng nhập đã tồn tại.',
                'password.required' => 'Mật khẩu không được bỏ trống.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
                'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
                'email.email' => 'Email không đúng định dạng.',
                'email.unique' => 'Email đã tồn tại.',
                'full_name.required' => 'Họ và tên không được bỏ trống.',
                'full_name.max' => 'Họ và tên không được dài quá 255 ký tự.',
                'address.max' => 'Địa chỉ không được dài quá 255 ký tự.',
                'phone.digits' => 'Số điện thoại phải đúng 10 chữ số và không được chứa ký tự khác.',
                'role.required' => 'Vai trò không được bỏ trống.',
                'role.in' => 'Vai trò không hợp lệ.',
                'image.image' => 'File phải là ảnh.',
                'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
                'image.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        $userUpdatedAtIso = optional($user->updated_at)->toISOString();

        if (!$request->updated_at || !$userUpdatedAtIso || $request->updated_at !== $userUpdatedAtIso) {
            return back()
                ->withErrors(['error' => 'Người khác đã thay đổi dữ liệu này. Vui lòng tải lại trang và thử lại.'])
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $userData = $request->except('password', 'password_confirmation', 'image', 'updated_at');

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destination = public_path('assets/images');
                
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                // Xóa ảnh cũ nếu có
                if ($user->image && file_exists(public_path($user->image))) {
                    unlink(public_path($user->image));
                }

                $file->move($destination, $filename);
                $userData['image'] = 'assets/images/' . $filename;
            }

            $user->update($userData);
            DB::commit();

            return redirect()->route('user.index')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }





    public function destroy(string $hashId)
    {
        $decoded = \Vinkla\Hashids\Facades\Hashids::decode($hashId);
    
        if (count($decoded) === 0) {
            return redirect()->route('user.index')->withErrors(['error' => 'Người dùng không tồn tại.']);
        }
    
        $user = User::find($decoded[0]);
    
        if (!$user) {
            return redirect()->route('user.index')->withErrors(['error' => 'Người dùng không tồn tại.']);
        }
    
        $user->delete();
    
        return redirect()->route('user.index')->with('success', 'Xoá người dùng thành công.');
    }
    
}