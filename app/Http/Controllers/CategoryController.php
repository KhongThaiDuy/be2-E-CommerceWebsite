<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('category_name', 'like', "%{$search}%")
                    ->orWhere('category_id', 'like', "%{$search}%");
            });
        }

        $sortOrder = $request->get('sort', 'asc');
        $perPage = 10;
        $page = $request->get('page', 1);

        if (!ctype_digit((string)$page) || intval($page) < 1) {
            return redirect()->route('categories.index')
                ->withErrors(['error' => 'Tham số trang (page) không hợp lệ.']);
        }

        $totalRecords = $query->count();
        $maxPage = (int) ceil($totalRecords / $perPage);
        if ($maxPage > 0 && intval($page) > $maxPage) {
            return redirect()->route('categories.index')
                ->withErrors(['error' => "Trang bạn yêu cầu không tồn tại. Tổng số trang tối đa là $maxPage."]);
        }

        $categories = $query->orderBy('category_id', $sortOrder)->paginate($perPage);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => [
                'required',
                'string',
                'max:191',
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
        ]);

        Category::create($request->only('category_name'));

        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => [
                'required',
                'string',
                'max:191',
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
            'updated_at' => 'required|date_format:Y-m-d H:i:s',
        ]);

        // Kiểm tra xung đột chỉnh sửa
        if (
            !$request->updated_at ||
            !$category->updated_at ||
            $request->updated_at !== $category->updated_at->format('Y-m-d H:i:s')
        ) {
            return back()->withErrors(['error' => 'Người khác đã thay đổi dữ liệu này. Vui lòng tải lại trang và thử lại.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $category->update($request->only('category_name'));
            DB::commit();

            return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Xoá danh mục thành công.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }

    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = Category::where('category_name', 'like', "%{$query}%")
            ->limit(5)
            ->get(['category_id', 'category_name']);

        $suggestions = $results->map(function ($category) {
            return [
                'id' => $category->category_id,
                'value' => $category->category_name,
                'label' => $category->category_name,
            ];
        });

        return response()->json($suggestions);
    }

    public function showProducts($id)
    {
        $category = Category::with('products')->findOrFail($id);
        $categories = Category::all();

        return view('categories.home', compact('category', 'categories'));
    }
}
