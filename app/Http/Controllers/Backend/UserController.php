<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // customers
    public function customers()
    {
        return view('backend.users.customers.index');
    }

    // create customer
    public function createCustomer()
    {
        return view('backend.users.customers.create');
    }

    // edit customer
    public function editCustomer($id)
    {
        return view('backend.users.customers.edit', ['userId' => $id]);
    }
}
