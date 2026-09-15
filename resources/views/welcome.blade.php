@extends('layouts.app')
@section('content')
<div class="shop-shell">
<nav class="nav"><a class="brand" href="{{ route('shop') }}"><span class="brand-mark">M</span> MORROW<span class="brand-dot">.</span></a><div class="nav-links"><a href="#catalog">Shop</a><a href="#story">Our edit</a><a href="{{ route('coupons.index') }}">Admin</a></div><a class="bag-link" href="#bag">Bag <span>{{ collect($cart)->sum('quantity') }}</span></a></nav>
<section class="hero"><div><p class="eyebrow">CURATED GOODS / 2026</p><h1>Small objects.<br><em>Good energy.</em></h1><p class="hero-copy">A considered edit of everyday pieces for slower mornings, clearer desks, and better rituals.</p><a class="button button-dark" href="#catalog">Explore the edit <span>↘</span></a></div><div class="hero-art"><div class="sun"></div><div class="vase vase-one"></div><div class="vase vase-two"></div><div class="hero-note">OBJECT<br>NO. 04<br><strong>USEFUL<br>BEAUTY</strong></div></div></section>
<section class="ticker"><span>FREE DELIVERY OVER $75</span><span>DESIGNED FOR DAILY RITUALS</span><span>30 DAY RETURNS</span></section>
<section class="catalog" id="catalog"><div class="section-head"><div><p class="eyebrow">THE CURRENT EDIT</p><h2>Good things, <em>well made.</em></h2></div><div class="filters"><a class="filter {{ !$activeCategory ? 'active' : '' }}" href="{{ route('shop') }}">All</a>@foreach($categories as $category)<a class="filter {{ $activeCategory === $category->slug ? 'active' : '' }}" href="?category={{ $category->slug }}">{{ $category->name }}</a>@endforeach</div></div><div class="product-grid">
@forelse($products as $product)
<article class="product"><div class="product-image" style="--accent: {{ $product->accent }}"><span class="product-number">0{{ $loop->iteration }}</span><span class="product-shape"></span><span class="product-category">{{ $product->category->name }}</span></div><div class="product-meta"><div><h3>{{ $product->name }}</h3><p>{{ $product->description }}</p></div><strong>${{ number_format($product->price, 2) }}</strong></div><form method="post" action="{{ route('cart.add', $product) }}">@csrf<button class="button button-light" type="submit">Add to bag <span>+</span></button></form></article>
@empty
<div class="empty-state">No products in this collection yet.</div>
@endforelse
</div></section>
<section class="bag-section" id="bag"><div class="section-head"><div><p class="eyebrow">YOUR BAG</p><h2>Ready when <em>you are.</em></h2></div><span class="muted">{{ count($cart) }} item types</span></div><div class="bag-layout"><div class="bag-items">
@forelse($cart as $item)
<div class="bag-item"><div class="mini-image" style="--accent: {{ $item['product']->accent }}"></div><div><h3>{{ $item['product']->name }}</h3><p>{{ $item['product']->category->name }} · ${{ number_format($item['product']->price, 2) }}</p></div><form method="post" action="{{ route('cart.update') }}" class="quantity-form">@csrf @method('PATCH')<input type="number" name="quantities[{{ $item['product']->id }}]" min="0" max="{{ $item['product']->stock }}" value="{{ $item['quantity'] }}"><button title="Update quantity">↗</button></form></div>
@empty
<div class="empty-state">Your bag is waiting for a good find.</div>
@endforelse
</div><aside class="summary"><p class="eyebrow">ORDER SUMMARY</p><div class="summary-row"><span>Subtotal</span><strong>${{ number_format($summary['subtotal'], 2) }}</strong></div>
@if($summary['coupon'])
<div class="summary-row discount"><span>{{ $summary['coupon']->code }} discount</span><strong>−${{ number_format($summary['discount'], 2) }}</strong></div>
@endif
<div class="summary-total"><span>Total</span><strong>${{ number_format($summary['total'], 2) }}</strong></div><form class="coupon-form" method="post" action="{{ route('cart.coupon') }}">@csrf<input name="code" placeholder="Have a coupon?" value="{{ $summary['coupon']->code ?? '' }}"><button class="button button-dark" type="submit">Apply</button></form>
@error('code')<p class="error">{{ $message }}</p>@enderror
@if($summary['coupon'])
<form method="post" action="{{ route('cart.coupon.remove') }}">@csrf @method('DELETE')<button class="text-button" type="submit">Remove coupon</button></form>
@endif
<button class="button button-accent checkout" type="button">Continue to checkout <span>→</span></button></aside></div></section>
<section class="story" id="story"><div><p class="eyebrow">A LITTLE MORE INTENTION</p><h2>Less noise.<br><em>More meaning.</em></h2></div><p>We look for the pieces that earn their place: tactile, useful, and quietly distinctive. This is a small shop with a practical point of view.</p></section>
<footer><a class="brand" href="{{ route('shop') }}"><span class="brand-mark">M</span> MORROW<span class="brand-dot">.</span></a><span>© {{ date('Y') }} Morrow Market</span><span>Built for everyday living.</span></footer>
</div>
@endsection
