<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\RegisterUser;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard'); // Redirect if already logged in
        }
    
        return view('admin.auth.login');
    }

    // Handle login manually
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user_name' => 'required',
            'password' => 'required|min:6',
        ]);
      
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard'); // Redirect on success
        }
    
        return back()->withErrors(['user' => 'Invalid credentials.']);
    }
 public function registrationList()
{
    $users = DB::table('register_user')->get(); // ✅ correct table name

    return view('admin.registration_list', compact('users'));
}

public function paymentList()
{
    $pays = DB::table('paymenrequest')->get(); // ✅ correct table name

    return view('admin.payment_list', compact('pays'));
}

    public function logout()
{
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login');
}

}
