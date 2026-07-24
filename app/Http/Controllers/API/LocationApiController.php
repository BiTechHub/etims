<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;

class LocationApiController extends Controller
{
      public function getStates()
    {
        return response()->json(State::all());
    }

    public function getDistricts($state_id)
    {
        return response()->json(District::where('state_id', $state_id)->get());
    }
}
