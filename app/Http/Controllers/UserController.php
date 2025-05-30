<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    private function convertFullWidthNumberToHalfWidth(string $str): string
    {
        $fullWidthNums = ['０','１','２','３','４','５','６','７','８','９'];
        $halfWidthNums = ['0','1','2','3','4','5','6','7','8','9'];

        return str_replace($fullWidthNums, $halfWidthNums, $str);
    }
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $sortOrder = $request->get('sort', 'asc');

        $perPage = 10;
        $page = $request->get('page', 1);

        // Chuyển số full-width sang half-width nếu có (nếu cần)
        $page = str_replace(['０','１','２','３','４','５','６','７','８','９'], ['0','1','2','3','4','5','6','7','8','9'], $page);

        // Kiểm tra page có phải số nguyên dương không
        if (!ctype_digit($page) || intval($page) < 1) {
            return redirect()->route('user.index')
                ->withErrors(['error' => 'Tham số trang (page) không hợp lệ.']);
        }

        $totalRecords = $query->count();
        $maxPage = (int) ceil($totalRecords / $perPage);

        // Nếu page vượt quá tổng số trang
        if ($maxPage > 0 && intval($page) > $maxPage) {
            return redirect()->route('user.index')
                ->withErrors(['error' => "Trang bạn yêu cầu không tồn tại. Tổng số trang tối đa là $maxPage."]);
        }

        $users = $query->orderBy('id', $sortOrder)->paginate($perPage);

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
            'full_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $trimmed = preg_replace('/[\s\x{3000}]+/u', '', $value);
                    if ($trimmed === '') {
                        $fail("Trường {$attribute} không được phép chỉ chứa khoảng trắng.");
                    }
                    if ($value !== strip_tags($value)) {
                        $fail("Trường {$attribute} không được chứa thẻ HTML.");
                    }
                },
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $trimmed = preg_replace('/[\s\x{3000}]+/u', '', $value);
                    if ($trimmed === '') {
                        $fail("Trường {$attribute} không được phép chỉ chứa khoảng trắng.");
                    }
                    if ($value !== strip_tags($value)) {
                        $fail("Trường {$attribute} không được chứa thẻ HTML.");
                    }
                },
            ],
            'phone' => 'nullable|digits:10',
            'role' => 'required|in:customer,admin',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        

        $user = new User($request->except('password', 'password_confirmation', 'image'));
        $user->password = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = strtolower($file->getClientOriginalExtension());
            if ($ext === 'pdf') {
                return back()->withErrors(['image' => 'File PDF không được phép tải lên.'])->withInput();
            }
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


    public function edit(string $token)
    {
        try {
            $user = User::where('token', $token)->firstOrFail();
            return view('admin.users.edit', compact('user'));
        } catch (ModelNotFoundException $e) {
            return view('errors.custom', ['message' => 'Người dùng không tồn tại hoặc token không hợp lệ.']);
        }
    }


        public function update(Request $request, string $token)
    {
        $request->merge([
            'phone' => $this->convertFullWidthNumberToHalfWidth($request->input('phone', '')),
        ]);
        
        $user = User::where('token', $token)->firstOrFail();
        $request->validate([
            //'username' => ['required', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6|confirmed',
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'full_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $trimmed = preg_replace('/[\s\x{3000}]+/u', '', $value);
                    if ($trimmed === '') {
                        $fail("Trường {$attribute} không được phép chỉ chứa khoảng trắng.");
                    }
                    if ($value !== strip_tags($value)) {
                        $fail("Trường {$attribute} không được chứa thẻ HTML.");
                    }
                },
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $trimmed = preg_replace('/[\s\x{3000}]+/u', '', $value);
                    if ($trimmed === '') {
                        $fail("Trường {$attribute} không được phép chỉ chứa khoảng trắng.");
                    }
                    if ($value !== strip_tags($value)) {
                        $fail("Trường {$attribute} không được chứa thẻ HTML.");
                    }
                },
            ],
            'phone' => 'nullable|digits:10',
            'role' => 'required|in:customer,admin',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        
        $userUpdatedAtIso = optional($user->updated_at)->toAtomString();

        if (
            !$request->updated_at ||
            !$user->updated_at ||
            $request->updated_at !== $user->updated_at->format('Y-m-d H:i:s')
        ) {
            return back()->withErrors(['error' => 'Người khác đã thay đổi dữ liệu này. Vui lòng tải lại trang và thử lại.'])->withInput();
        }
        
        

        DB::beginTransaction();
        try {
            $userData = $request->except('password', 'password_confirmation', 'image', 'updated_at');

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = strtolower($file->getClientOriginalExtension());
                if ($ext === 'pdf') {
                    return back()->withErrors(['image' => 'File PDF không được phép tải lên.'])->withInput();
                }
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





    public function destroy(string $token)
    {
        $user = User::where('token', $token)->first();

        if (!$user) {
            return redirect()->route('user.index')->withErrors(['error' => 'Người dùng không tồn tại.']);
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Xoá người dùng thành công.');
    }

    
    public function suggestions(Request $request)
    {
        $keyword = $request->get('keyword', '');

        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $results = User::where(function($q) use ($keyword) {
            $q->where('username', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%");
        })->limit(10)->get(['id', 'username', 'email']);

        $suggestions = $results->map(function ($user) {
            return [
                'id' => $user->id,
                'value' => $user->username,
                'label' => $user->username . ' (' . $user->email . ')',
            ];
        });

        return response()->json($suggestions);
    }


}