<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\CouponService;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function index(Request $request, CouponService $couponService)
    {
        $category = $request->get('category');
        $products = Product::with('category')->where('is_active', true)->when($category, function ($query) use ($category) {
            $query->whereHas('category', function ($categoryQuery) use ($category) {
                $categoryQuery->where('slug', $category);
            });
        })->orderBy('id')->get();

        $cart = $this->cart($request);
        $summary = $this->summary($cart, $couponService);

        return view('welcome', [
            'products' => $products,
            'categories' => Category::withCount('products')->orderBy('name')->get(),
            'cart' => $cart,
            'summary' => $summary,
            'activeCategory' => $category,
        ]);
    }

    public function add(Request $request, Product $product)
    {
        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = min(($cart[$product->id] ?? 0) + 1, $product->stock);
        $request->session()->put('cart', array_filter($cart));
        return back()->with('status', $product->name . ' added to your bag.');
    }

    public function update(Request $request)
    {
        $quantities = $request->input('quantities', []);
        $products = Product::whereIn('id', array_keys($quantities))->get()->keyBy('id');
        $cart = [];
        foreach ($quantities as $id => $quantity) {
            if (isset($products[$id]) && (int) $quantity > 0) {
                $cart[$id] = min((int) $quantity, $products[$id]->stock);
            }
        }
        $request->session()->put('cart', $cart);
        return back()->with('status', 'Bag updated.');
    }

    public function applyCoupon(Request $request, CouponService $couponService)
    {
        $request->validate(['code' => 'required|string']);
        try {
            $cart = $this->cart($request);
            $summary = $this->summary($cart, $couponService, null);
            $coupon = $couponService->validate(strtoupper($request->code), $summary['subtotal']);
            $request->session()->put('coupon', $coupon->code);
            return back()->with('status', $coupon->code . ' applied to eligible items.');
        } catch (\Exception $exception) {
            return back()->withErrors(['code' => $exception->getMessage()]);
        }
    }

    public function removeCoupon(Request $request)
    {
        $request->session()->forget('coupon');
        return back()->with('status', 'Coupon removed.');
    }

    private function cart(Request $request): array
    {
        $ids = $request->session()->get('cart', []);
        $products = Product::with('category')->whereIn('id', array_keys($ids))->get()->keyBy('id');
        $items = [];
        foreach ($ids as $id => $quantity) {
            if (isset($products[$id])) {
                $items[] = ['product' => $products[$id], 'quantity' => $quantity];
            }
        }
        return $items;
    }

    private function summary(array $items, CouponService $couponService, $couponCode = null): array
    {
        $subtotal = collect($items)->sum(function ($item) {
            return $item['product']->price * $item['quantity'];
        });
        $couponCode = $couponCode ?: request()->session()->get('coupon');
        $coupon = $couponCode ? Coupon::where('code', $couponCode)->first() : null;
        $discount = $coupon ? $couponService->discountForItems($coupon, $items)['discount'] : 0;

        return ['subtotal' => round($subtotal, 2), 'discount' => $discount, 'total' => round($subtotal - $discount, 2), 'coupon' => $coupon];
    }
}