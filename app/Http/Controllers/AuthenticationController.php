<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    // Guest Login
    public function guestLogin()
    {
        return view('auth.guest-login');
    }

    public function authenticateGuest(Request $request)
    {
        // Demo: Check if credentials match demo guest account
        if ($request->email === 'guest@example.com' && $request->password === 'password') {
            session(['user_type' => 'guest', 'user_email' => $request->email, 'user_name' => 'Demo Guest']);
            return redirect('/guest/dashboard');
        }

        return redirect('/guest/login')->with('error', 'Invalid credentials');
    }

    // Guest Dashboard
    public function guestDashboard()
    {
        if (session('user_type') !== 'guest') {
            return redirect('/guest/login');
        }

        return view('dashboards.guest-dashboard');
    }

    // Owner Login
    public function ownerLogin()
    {
        return view('auth.owner-login');
    }

    public function authenticateOwner(Request $request)
    {
        // Demo: Check if credentials match demo owner account
        if ($request->email === 'owner@example.com' && $request->password === 'password') {
            session(['user_type' => 'owner', 'user_email' => $request->email, 'user_name' => 'Demo Owner']);
            return redirect('/owner/dashboard');
        }

        return redirect('/owner/login')->with('error', 'Invalid credentials');
    }

    // Owner Dashboard
    public function ownerDashboard()
    {
        if (session('user_type') !== 'owner') {
            return redirect('/owner/login');
        }

        return view('dashboards.owner-dashboard');
    }

    // Admin Login
    public function adminLogin()
    {
        return view('auth.admin-login');
    }

    public function authenticateAdmin(Request $request)
    {
        // Demo: Check if credentials match demo admin account
        if ($request->email === 'admin@example.com' && $request->password === 'password') {
            session(['user_type' => 'admin', 'user_email' => $request->email, 'user_name' => 'Demo Admin']);
            return redirect('/admin/dashboard');
        }

        return redirect('/admin/login')->with('error', 'Invalid credentials');
    }

    // Admin Dashboard
    public function adminDashboard()
    {
        if (session('user_type') !== 'admin') {
            return redirect('/admin/login');
        }

        return view('dashboards.admin-dashboard');
    }

    // Logout
    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
}

