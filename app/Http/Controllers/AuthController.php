<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return $this->redirectByrole(Auth::user()->role);
        }   
    }

    public function redirectByrole(string $role)
    {
        return match($role){
            // 'admin' => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('users.index'),
            // 'parent' => redirect()->route('parent.dashboard'),
            // 'student' => redirect()->route('student.dashboard')
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
