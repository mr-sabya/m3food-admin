<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DealController extends Controller
{
    //
    public function index()
    {
        return view('backend.deal.index');    
    }
    
    // create
    public function create()
    {
        return view('backend.deal.create');    
    }

    // edit
    public function edit($id)
    {
        return view('backend.deal.edit', ['dealId' => $id]);    
    }
}
