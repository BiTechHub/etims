<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
  public function register_user()
{
    $countries = DB::table('countries')
        ->where('isDeleted', 'N')
        ->orderBy('name', 'ASC')
        ->get();

    $states = DB::table('states')
        ->where('isDeleted', 'N')
        ->orderBy('name', 'ASC')
        ->get();

    $organization = DB::table('organization')
        ->where('isDeleted', 'N')
        ->orderBy('name', 'ASC')
        ->get();

    $branch = DB::table('branch')
        ->where('isDeleted', 'N')
        ->orderBy('name', 'ASC')
        ->get();

    $payment_type = DB::table('payment_type')
        ->where('isDeleted', 'N')
        ->orderBy('name', 'ASC')
        ->get();

    return view('register_user', compact(
        'countries',
        'states',
        'organization',
        'branch',
        'payment_type'
    ));
}


  

public function register_user_post(Request $request)
{
    // Validation
    $request->validate([
        'first_name'   => 'required',
        'last_name'    => 'required',
        'email'        => 'required|email',
        'phone'        => 'required|digits:10',
        'branch'       => 'required|string|max:100',
        'state'        => 'required|string|max:100',
        'Country'      => 'required|string|max:100',
        'organization' => 'required|string|max:255',
        'payment'      => 'required',
    ]);

    // Generate Unique User Key
    $lastUser = DB::table('register_user')
    ->orderBy('id', 'desc')
    ->first();

if ($lastUser) {
    $lastNumber = (int) str_replace('BIRD', '', $lastUser->user_key);
    $newNumber = $lastNumber + 1;
} else {
    $newNumber = 100001;
}

$user_key = 'BIRD' . $newNumber;

$payment_type = DB::table('payment_type')
        ->where('id', $request->payment)
        ->first();

    // Insert Data
    $id = DB::table('register_user')->insertGetId([
        'user_key'     => $user_key,
        'first_name'   => $request->first_name,
        'last_name'    => $request->last_name,
        'email'        => $request->email,
        'phone'        => $request->phone,
        'branch'       => $request->branch,
        'state'        => $request->state,
        'Country'      => $request->Country,
        'payment'      => $payment_type->amount,
        'payment_type' => $payment_type->id,
        'organization' => $request->organization,
        'job_title'    => $payment_type->name,
        'created_at'   => now(),
    ]);

    // Redirect
    return redirect('confirm-payment/'.$user_key)
        ->with('success', 'Registration Successful. User Key: '.$user_key);
}


  public function confirm_payment($id)
    {

      // 1️⃣ Fetch the house
    $house = DB::table('register_user')
        ->where('user_key', $id)
        ->first();

    if (!$house) {
        return redirect()->back()->with('error', 'House not found');
    }

   return view('show_payment2', compact('house'));

}

}
