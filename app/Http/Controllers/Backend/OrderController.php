<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function  index()
    {
        return view('backend.pages.orders.index');
    }

    // create
    public function create()
    {
        return view('backend.pages.orders.create');    
    }

    // invoice view
    public function invoice($orderId)
    {
        return view('backend.pages.orders.invoice', compact('orderId'));
    }

    // manage view
    public function manage($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('backend.pages.orders.manage', compact('order'));
    }
}
