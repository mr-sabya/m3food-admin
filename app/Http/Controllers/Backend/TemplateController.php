<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    // header
    public function header()
    {
        return view('backend.template.header');    
    }


    // footer
    public function footer()
    {
        return view('backend.template.footer');    
    }

    // theme
    public function theme()
    {
        return view('backend.template.theme');    
    }
}
