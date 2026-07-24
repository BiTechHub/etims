<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AgencyType;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function create()
    {
        // Fetch all agency groups to populate the dropdown
        $agencyTypes = AgencyType::all();
        
        // Return the view with agency groups
        return view('admin.masters.agency', compact('agencyTypes'));
    }


    public function getData(Request $request)
    {
        $agencies = Agency::select([
            'id','agency_type_id','name','sponsor_bank','chairman','address','state','city','pincode',
            'phone','emailid',


]);
    
        return DataTables::of($agencies)
            ->addColumn('action', function($agency) {
                return '';
            })
            ->make(true);
    }
}
