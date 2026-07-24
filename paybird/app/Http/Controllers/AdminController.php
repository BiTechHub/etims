<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\State;
use App\Models\Country;
use App\Models\District;
use Illuminate\Support\Facades\DB;
class AdminController extends Controller
{

public function index(){

    $now = Carbon::now();

    // Total Sales
    $totalSales = Payment::sum('amount');

    // Monthly Sales
    $monthlySales = Payment::whereMonth('created_at', $now->month)
        ->whereYear('created_at', $now->year)
        ->sum('amount');

    // Today Sales
    $todaySales = Payment::whereDate('created_at', $now->toDateString())
        ->sum('amount');

    

    return view('index', compact(
        'totalSales',
        'monthlySales',
        'todaySales'
    ));
}

public function state(){
    
     $states = State::all();
     return view('admin.state')->with('states',$states);
}

public function storeDistrict(Request $request)
{
    District::create([
        'state_id' => $request->state_id,
        'name' => $request->name
    ]);

    return back()->with('success','District Added');
}

public function updateDistrict(Request $request, $id)
{
    $district = District::find($id);
    $district->name = $request->name;
    $district->save();

    return back()->with('success','District Updated');
}
public function countries()
{
     $country = Country::all();
     return view('admin.country')->with('country',$country);
}



public function storeCountry(Request $request)
{
    
    Country::create([
        'id' => $request->id,
        'name' => $request->name
    ]);
    

    return back()->with('success','Country Added');
}


public function payment_type()
{
    $payment_type = DB::table('payment_type')
        ->where('isDeleted', 'N')
        ->orderBy('id', 'ASC')
        ->get();

     return view('admin.payment_type')->with('payment_type',$payment_type);
}

public function payment_store(Request $request)
{
    $request->validate([
        'name'   => 'required|string|max:100',
        'amount' => 'required|numeric|min:1',
    ]);

    DB::table('payment_type')->insert([
        'name'       => $request->name,
        'amount'     => $request->amount,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Payment Type Added Successfully.');
}




public function branch()
{
    $branch = DB::table('branch')
        ->where('isDeleted', 'N')
        ->orderBy('name', 'ASC')
        ->get();

     return view('admin.branch')->with('branch',$branch);
}

public function branch_store(Request $request)
{
    $request->validate([
        'name'   => 'required|string|max:200',
    ]);

    DB::table('branch')->insert([
        'name'       => $request->name,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Payment Type Added Successfully.');
}




public function organization()
{
    $organization = DB::table('organization')
        ->where('isDeleted', 'N')
        ->orderBy('id', 'ASC')
        ->get();

     return view('admin.organization')->with('organization',$organization);
}

public function organization_store(Request $request)
{
    $request->validate([
        'name'   => 'required|string|max:200',
    ]);

    DB::table('organization')->insert([
        'name'       => $request->name,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Payment Type Added Successfully.');
}

}