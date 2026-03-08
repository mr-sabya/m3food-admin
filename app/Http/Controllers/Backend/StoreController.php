<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    //
    public function WareHouse()
    {
        return view('backend.warehouse.index');    
    }
}
