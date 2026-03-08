<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showForgotPassword()
    {
        return view('backend.auth.forgot-password');
    }

    public function showResetPassword($token)
    {
        return view('backend.auth.reset-password', ['token' => $token]);
    }

    /**
     * Show the admin profile update page.
     */
    public function profile()
    {
        return view('backend.profile');
    }

    /**
     * Show the admin change password page.
     */
    public function changePassword()
    {
        return view('backend.change-password');
    }
}
