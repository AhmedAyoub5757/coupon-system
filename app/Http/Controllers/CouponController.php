<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.coupons.index', ['coupons' => Coupon::with('category')->latest()->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.coupons.create', [
            'categories' => Category::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Coupon::create($this->validated($request));
        return redirect()->route('coupons.index')->with('status', 'Coupon created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        return redirect()->route('coupons.edit', $coupon);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', [
            'coupon' => $coupon,
            'categories' => Category::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update($this->validated($request));
        return redirect()->route('coupons.index')->with('status', 'Coupon updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')->with('status', 'Coupon removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'code' => 'required|string|max:40',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'scope' => 'required|in:all,category,products',
            'category_id' => 'nullable|integer',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer',
            'min_cart_value' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');
        $data['category_id'] = $data['scope'] === 'category' ? ($data['category_id'] ?? null) : null;
        $data['product_ids'] = $data['scope'] === 'products' ? ($data['product_ids'] ?? []) : [];

        return $data;
    }
}
