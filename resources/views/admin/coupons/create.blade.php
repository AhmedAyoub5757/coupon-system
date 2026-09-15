@extends('layouts.app')
@section('content')
<div class="admin-shell"><nav class="nav"><a class="brand" href="{{ route('shop') }}"><span class="brand-mark">M</span> MORROW<span class="brand-dot">.</span></a><a class="button button-light" href="{{ route('coupons.index') }}">← Back to coupons</a></nav><div class="form-heading"><p class="eyebrow">PROMOTION BUILDER</p><h1>New coupon <em>rule.</em></h1></div><form class="coupon-editor" method="post" action="{{ route('coupons.store') }}">@csrf @include('admin.coupons.form', ['coupon' => null])</form></div>
@endsection