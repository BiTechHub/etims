<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AdminAuthController extends Controller
{
    public function showLogin(Request $request)
    {

        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard'); // Redirect if already logged in
        }
		$type=$request->type;
    
        return view('admin.auth.login',compact('type'));
    }

    // Handle login manually
    

 public function encrypt_token(Request $request)
	{
	    $encrypted = Crypt::encryptString($request->token_id);
	
	    return response()->json([
	        'token' => $encrypted
	    ]);
	}


public function login(Request $request)
	{
		//dd($request->password);
	    $credentials = $request->validate([
	        'user_name' => 'required',
	        'password' => 'required',
	    ]);
	
	$password =	Crypt::decryptString($request->password);
	//dd($password);
	    if (Auth::guard('admin')->attempt(['user_name' => $request->user_name,'password' => $password])) {
	
	        // ? Secure session
	        $request->session()->regenerate();
	
	        // ? Track activity
	        session([
	            'last_activity' => time()
	        ]);
	
	        return redirect()->route('admin.dashboard');
	    }
	
	    return back()->withErrors(['user' => 'Invalid credentials.']);
	}

//     public function logout()
// {
//     Auth::guard('admin')->logout();
//     return redirect()->route('admin.login');
// }

public function logout()
{
    Auth::guard('admin')->logout();
    return redirect()->route('welcome');
}

}
