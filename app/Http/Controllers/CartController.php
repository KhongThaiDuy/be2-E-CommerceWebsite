<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        return view('cart.index', compact('cart')); // Không cần truyền $totalQuantity nữa
    }


    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);

        $cart[$product->product_id] = [
            'name' => $product->product_name,
            'price' => $product->price,
            'quantity' => ($cart[$product->product_id]['quantity'] ?? 0) + 1,
            'image' => $product->image1,
        ];

        session(['cart' => $cart]);

        return back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->product_id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng!');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->withErrors(['error' => 'Giỏ hàng đang trống']);
        }

        DB::beginTransaction();
        try {
            $total = array_reduce($cart, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);

            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'status' => 'pending',
            ]);

            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('cart.index')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi đặt hàng: ' . $e->getMessage()]);
        }
    }
}
