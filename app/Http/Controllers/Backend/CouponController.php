<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // coupons page
    public function index()
    {
        return view('backend.coupon.index');
    }

    // create coupon
    public function create()
    {
        return view('backend.coupon.create');
    }

    // edit coupon
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('backend.coupon.edit', compact('coupon'));
    }
}
