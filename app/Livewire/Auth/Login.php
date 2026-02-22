<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function authenticate()
    {
        $this->validate();

        // Attempt to log in using the 'admin' guard
        if (!Auth::guard('admin')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'), // Uses Laravel's default auth.failed message
            ]);
        }

        session()->regenerate();

        // Redirect to the dashboard or intended page for admins
        // You can fetch this from your LoginController's $redirectTo if you want consistency
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
