<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        return view('backend.home.index');
    }

   
    


    // brands page
    public function brands()
    {
        return view('backend.brand.index');
    }

    

    // tags page
    public function tags()
    {
        return view('backend.tag.index');
    }
}
