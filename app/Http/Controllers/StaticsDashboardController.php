<?php

namespace App\Http\Controllers;

use App\Models\ProgrammeManagement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
   
class StaticsDashboardController extends Controller
{
  

public function index(Request $request)
{


$programmes=ProgrammeManagement::select('id','unique_id','from_date','to_date','status','prog_dir_2')->get();
//dd($programmes);

return view('statics-dashboard',compact('programmes'));

}



}
