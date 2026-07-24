<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use App\Mail\AttendanceMarkedPresent;

use App\Mail\CheckoutMail;

use App\Models\BedAllocation;

use App\Models\Block;

use App\Models\FeedbackModel;

use App\Models\Nomination;

use App\Models\Participant;

use App\Models\Programme;

use App\Models\ProgrammeManagement;

use App\Models\Query;

use App\Models\Room;

use App\Models\Typesofroom;

use GuzzleHttp\Promise\Create;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;

use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Str;
use DB;


use Illuminate\Support\Facades\Log;



class HostelController extends Controller

{

    public function view_attendence(Request $request)

    {

        // 1. Distinct calendar years

        $distinctYears = ProgrammeManagement::select('financial_year')

                            ->distinct()

                            ->orderBy('financial_year','desc')

                            ->pluck('financial_year');

    

        // 2. All programmes

        $programmes = ProgrammeManagement::select(['id','financial_year','title'])

                        ->get();

    

        // 3. Base query for nominations

        $query = Nomination::with(['agencyType','agency','participants']);

    

        // 4. Apply filters

        if ($request->filled('cal_year')) {

            // join through programme if needed, assuming nominations track programme_id

            $query->whereHas('programme', function($q) use ($request) {

                $q->where('financial_year', $request->cal_year);

            });

        }

        if ($request->filled('programme')) {

            $query->where('programme_id', $request->programme);

        }

    

        // 5. Fetch results

        $nominations = $query

            ->orderBy('nomination_date','desc')

            ->get();

    

        // 6. Return view

        return view('Hostel.hostel_view', [

            'distinctYears'      => $distinctYears,

            'programmes'         => $programmes,

            'nominations'        => $nominations,

            'selectedYear'       => $request->cal_year,

            'selectedProgramme'  => $request->programme,

        ]);

    }

    public function getByProgramme(Request $request)

    {

        $programmeId = $request->programme_id;

        $participants = Participant::whereHas('nomination', function ($q) use ($programmeId) {

            $q->where('programme_id', $programmeId);

        })

        ->with(['nomination.agencyType:id,name',

                'nomination.agency:id,name',

        

        ])  

        ->get();

        

    

        return response()->json(['data' => $participants]);

    }

    public function updateAttendence(Request $request)

{

    // Validate incoming request

    $request->validate([
        'participant_ids' => 'required|array',
        'participant_ids.*' => 'exists:nomination_participants,id',
        'status' => 'required|string',
    ]);



    // Update the status for all selected participants

    Participant::whereIn('id', $request->participant_ids)

        ->update(['hostel_attendence' => $request->status]);



    // If the status is 'Present', send email notifications

    if (strtolower($request->status) === 'present') {

        $participants = Participant::whereIn('id', $request->participant_ids)->get();



        foreach ($participants as $participant) {

            if (!empty($participant->email)) {

                Mail::to($participant->email)->send(new AttendanceMarkedPresent($participant));

            }

        }

    }



    // Return a success response

    return redirect()->back()->with('success', 'Attendance updated successfully.');



}



public function updateDatetime(Request $request)

{

    $request->validate([

        'participant_ids' => 'required|array',

        'date' => 'required|date',

        'time' => 'required'

    ]);



    // Update participants date and checkout_time

    Participant::whereIn('id', $request->participant_ids)->update([

        'date' => $request->date,

        'checkout_time' => $request->time

    ]);



    // Fetch updated participants csc{ee}

    $participants = Participant::whereIn('id', $request->participant_ids)->get();

 $roomIds = $participants->pluck('rooms_id')->filter()->unique();



    // Set bed_id to null for those participants

    Participant::whereIn('id', $request->participant_ids)->update(['bed_id' => null,'rooms_id'=>0]);



    BedAllocation::whereIn('participant_id',$request->participant_ids)->update(['participant_id'=>null,'programme_id'=>null,'is_available'=>null]);

    

  Room::whereIn('id', $roomIds)->update(['is_available' => null]);

    // Send mail to each participant

    foreach ($participants as $participant) {

        Mail::to($participant->email)->send(new CheckoutMail($participant));

    }



    return response()->json(['success' => true]);

}



public function room()

{

    $blocks = Block::select('id', 'name')->get();

    $types = Typesofroom::select('id', 'types_of_rooms')->get();




    return view('Hostel.room', compact('blocks', 'types'));

}







public function store_room(Request $request)

{

    $request->validate([

        'block_id' => 'required|exists:blocks,id',

        'room_type' => 'required',
     

        'generated_room_name' => 'required|array',

       'generated_room_name.*' => 'required|distinct|unique:rooms,room_number',

    ]);



    try {

   DB::beginTransaction();


    foreach ($request->generated_room_name as $roomNumber) {

    $room = Room::where('room_number',$roomNumber)->first();

    if($room){
        return response()->json(['success' => false ,'message' => 'Room number already exists.'],422);
    }

    $room = Room::create([
        'block_id'    => $request->block_id,
        'room_type'   => $request->room_type,
        'room_number' => $roomNumber,
    ]);
}

DB::commit();

        return response()->json(['success' => true ,'message' => 'Rooms and beds added successfully.']);

    } catch (\Exception $e) {

        DB::rollback();

        return response()->json(['success' => false ,'message' => 'Error: ' . $e->getMessage()]);

    }

}









private function createBedsForRoom(Room $room)

{

    $noOfBeds = $this->getNoOfBedsForRoomType($room->room_type);



    for ($bedNo = 1; $bedNo <= $noOfBeds; $bedNo++) {

        BedAllocation::create([

            'block_id'   => $room->block_id,  // ✅ include block_id

            'room_number'    => $room->id,

            'bed_number' => $bedNo,

        ]);

    }

}



private function getNoOfBedsForRoomType($roomType)

{

    switch ($roomType) {

        case 1: return 1; // Single

        case 2: return 2; // Double

        default: return 1; // Default to 1

    }

}

  

    

    

    



    //get the ajax data for the room



public function getData(Request $request)

{

$search = $request->search['value'];


    $room = Room::with(['block:id,name','type:id,types_of_rooms']);


    if($search){

        $room->where('room_number','LIKE','%'.$search.'%');

    }

    if($request->room_type){
        $room->where('room_type',$request->room_type);
    }

    if($request->block_id){
        $room->where('block_id',$request->block_id);
    }

        $room->select(['id', 'room_number', 'block_id','room_type','is_active']); // Include all required FKs



    return DataTables::of($room)

        ->addColumn('block_name', function ($room) {

            return $room->block->name ?? 'N/A';

        })

        ->addColumn('type_of_room', function ($room) {

            return $room->type->types_of_rooms ?? 'N/A';

        })

        ->rawColumns(['block_name', 'type_of_room']) // Only needed if you use HTML here

        ->make(true);

}







public function room_list(){

  $blocks = Block::select('id', 'name')->get();

   $roomTypes=Typesofroom::all();

    return view('Hostel.list',compact('blocks','roomTypes'));

}



   

public function destroy($id)

{

    try {

        $room = Room::findOrFail($id);



        // Assuming beds are stored using room_number = Room::number

        $beds = BedAllocation::where('room_number', $id);

        



     

        // Delete matching beds

        $beds->delete();



        // Delete the room

        $room->delete();



        return response()->json([

            'success' => true,

            'message' => 'Room deleted successfully'

        ]);

    } catch (\Exception $e) {

        return response()->json([

            'success' => false,

            'message' => 'Error deleting room: ' . $e->getMessage()

        ], 500);

    }

}



public function destroyBlock($id)

{



    



        $room = Block::findOrFail($id);

         $room->delete();

        

}



public function room_edit($id){



 



$beds = BedAllocation::where('room_number', $id)->get();





    $Room = Room::findOrFail($id);

  



    return view('Hostel.edit', compact('Room','beds'));

}





//function for the room update



public function update(Request $request)

{

    $room = Room::findOrFail($request->id);

    $room->number = $request->number;

    $room->beds = $request->beds;

    $room->save();



    return redirect()->back()->with('success', 'Room updated successfully.');

}



public function queries(){

    return view('Hostel.queries');

}





public function store_queries(Request $request)

{

    // Validate input

    $validated = $request->validate([

        'name' => 'required|string|max:255',

        'phone' => 'required|numeric',

        'email' => 'required|email|max:255',

        'room' => 'nullable|string|max:50',

        'message' => 'required|string|max:1000',

    ]);



    // Store the query

    $query = new Query();

    $query->name = $request->input('name');

    $query->phone = $request->input('phone');

    $query->email = $request->input('email');

    $query->room = $request->input('room');

    $query->message = $request->input('message');

    $query->save();



    // Flash success message

    return redirect()->back()->with('success', 'Your query has been submitted successfully!');

}



//this is the function for the store feedbacks



public function store_feedback(Request $request){



    $validated = $request->validate([

        'name' => 'required|string|max:255',

        'email' => 'required|email|max:255',

        'phone' => 'required|string|max:20',



        'check_out_experience' => 'required|string|in:Excellent,Good,Fair,Poor',

        'cleanliness' => 'required|string|in:Excellent,Good,Fair,Poor',

        'housekeeping' => 'required|string|in:Excellent,Good,Fair,Poor',

        'staff_service' => 'required|string|in:Excellent,Good,Fair,Poor',

        'restaurant_food' => 'required|string|in:Excellent,Good,Fair,Poor',

        'amenities' => 'required|string|in:Excellent,Good,Fair,Poor',

        'overall_rating' => 'required|string|in:Excellent,Good,Fair,Poor',

       'programme_id' => 'required|exists:programmes,id',

        'comments' => 'nullable|string',

    ]);



    FeedbackModel::create($validated);



    return redirect()->back()->with('success', 'Thank you for your feedback!');

}



public function feedback_form($id){

    $participant = Participant::findOrFail($id);

    $programme=Programme::findOrFail($participant->programme_id);

    return view('Hostel.feedback', compact('participant','programme'));

}





public function view_beds(){



  $blocks = Block::select('id', 'name')->get();

   $roomTypes=Typesofroom::all();
        // Return the view with block data

        return view('Hostel.create-room', compact('blocks','roomTypes'));

}







//function for the block



public function block(){



    return view('Hostel.block');

}





public function store_block(Request $request)

{

    $validated = $request->validate([

   'name' => 'required|unique:blocks,name',



    ]);



    Block::create([

        'name' => $request->name,

    ]);



    return redirect()->back()->with('success', 'Block added successfully!');

}







//get block data



public function getDataBlock(Request $request)

{

    $blocks= Block::select(['id', 'name']);



    return DataTables::of($blocks)

        ->make(true);

}



public function getBedTypesByBlock($blockId)

{

    // Fetch bed types for the given block

    $bedTypes = Typesofroom::where('block_id', $blockId)->get(['id', 'types_of_rooms']);



    // Return as JSON for frontend to use in dropdown

    return response()->json($bedTypes);

}







public function store_bed(Request $request)

{



    $request->validate([

        'block' => 'required|exists:blocks,id',

        'room_number' => 'required|array',

        'bed_number' => 'required|array',

        'room_number.*' => 'exists:rooms,room_number',

        'bed_number.*' => 'integer|between:1,10',

    ]);



    $blockId = $request->block;

    $roomNumbers = $request->room_number;

    $bedNumbers = $request->bed_number;



    $usedRoomNumbers = []; // To track duplicates in the same request



    foreach ($roomNumbers as $index => $roomNumber) {

        $bedNumber = $bedNumbers[$index];



        // Check for duplicates within the same request

        if (in_array($roomNumber, $usedRoomNumbers)) {

            return redirect()->back()->withErrors(['room_number' => "Room $roomNumber is duplicated in the form."])->withInput();

        }



        // Check if this room is already allocated in this block in the database

        $exists = BedAllocation::where('block_id', $blockId)

            ->where('room_number', $roomNumber)

            ->exists();



        if ($exists) {

            return redirect()->back()->withErrors(['room_number' => "Room $roomNumber has already been allocated in the selected block."])->withInput();

        }



        // Passed checks, store it

        BedAllocation::create([

            'block_id' => $blockId,

            'room_number' => $roomNumber,

            'bed_number' => $bedNumber,

        ]);  



        $usedRoomNumbers[] = $roomNumber;

    }



    return redirect()->back()->with('success', 'Room and Bed Allocation Saved Successfully!');

}

public function showAllotmentForm()

{

    // Fetch all programmes

    $programmes = Programme::select('id', 'title')->get();

    $blocks=Block::select('id','name')->get();

    // Optionally: Fetch all blocks (or filter based on availability, your logic)

    $blocks = Block::select('id', 'name')->get();



    $types=Typesofroom::select('id','types_of_rooms')->get();

    return view('Hostel.Room_allotment', compact('programmes','blocks','types'));

}





// In your controller, make sure the method is correct:

public function getParticipants($programme_id)

{

    // Fetch participants based on the programme_id passed as a parameter

    $participants = Participant::with('bed','bed.room')->where('programme_id', $programme_id)

                            ->where('status', 'paid')->get();



    return response()->json($participants);

}

public function getAllotRooms(Request $request)
{
    $request->validate([
        'programme' => 'required',
        'participant' => 'required',
        'block_id' => 'required',
        'room_type' => 'required',
    ]);


    $programmeId = base64_decode($request->programme);

    $participantId = base64_decode($request->participant);

    $blockId = base64_decode($request->block_id);
    $roomType = base64_decode($request->room_type);

    $block = Block::find($blockId);


    $participant = Participant::find($participantId);

    $programme = Programme::find($programmeId);

    $rooms = Room::with('type','bed_allocation')->where('block_id', $blockId)
        ->where('room_type', $roomType)
        ->whereNull('is_available')
        ->get();



       // dd($rooms);

    return view('Hostel.allot_rooms', compact('participant', 'programme','rooms','block'));

}

public function saveAllotRooms(Request $request)
{
    $request->validate([
        'participant_id' => 'required',
        'programme_id' => 'required',
        'bed_no' => 'required',
        'room_id' => 'required',
        
    ]);

    try {

    $participant = Participant::findOrFail($request->participant_id);

    $bed =BedAllocation::where('participant_id', $request->participant_id)
        ->where('programme_id', $request->programme_id)
        ->first();

        if ($bed) {
            $bed->bed_number = $request->bed_no;
            $bed->room_number = $request->room_id;
            $bed->save();
            return response()->json(['message' => 'Allot Successfully']);
        }
        BedAllocation::create([
            'participant_id' => $request->participant_id,
            'programme_id' => $request->programme_id,
            'bed_number' => $request->bed_no,
            'room_number' => $request->room_id,
            'room_id' => $request->room_id,
            'status' => 2,
            'is_available' => 0,
          
        ]);

        $participant->checked_in_at = now();
        $participant->save();

        return response()->json(['message' => 'Allot Successfully']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error: ' . $e->getMessage()]);
    }
        





}

public function getAvailableUnits(Request $request)

{

    $blockId = $request->block_id;

    $roomType = $request->room_type;



  $rooms = Room::where('block_id', $blockId)

    ->where('room_type', $roomType)

    ->whereNull('is_available')

    ->where('is_active', 1)

    ->get(['id', 'room_number']);



    return response()->json($rooms);

}









public function typeOfRooms(){

    return view('Hostel.type_of_rooms');

}





public function store_type(Request $request)

{

    $validated = $request->validate([

   'types_of_rooms' => 'required',
   'avaible_beds' => 'required',
    ]);



    Typesofroom::create([

        'types_of_rooms' => $request->types_of_rooms,
        'avaible_beds' => $request->avaible_beds,

    ]);



    return redirect()->back()->with('success', 'Room type added successfully!');

}



public function showRoomNumberAllotment(Request $request)

{

    // Retrieve all blocks and room types

    $blocks = Block::select('id', 'name')->get();

    $types = Typesofroom::select('id', 'types_of_rooms')->get();



    // Initialize the allottedRooms as an empty array

    $allottedRooms = [];



    // If block and type are selected, fetch the rooms based on those filters

    if ($request->has('block_id') && $request->has('room_type_id')) {

        $allottedRooms = Room::where('block_id', $request->block_id)

                             ->where('room_type', $request->room_type_id)

                             ->get();

    }



    // Return the view with the required data

    return view('Hostel.room_number_allotment', compact('blocks', 'types', 'allottedRooms'));

}





public function updateRoomNumberAjax(Request $request)

{



 

   // Validate the input

       // Validate the request

       $request->validate([

        'room_id' => 'required|exists:roommanages,id',

        'room_number' => 'required|string|max:255',

    ]);



    try {

        // Find the room

        $room = Room::find($request->room_id);



        if (!$room) {

            return response()->json(['success' => false, 'message' => 'Room not found.'], 404);

        }



        // Update room number

        $room->room_number = $request->room_number;



        if ($room->isDirty('room_number')) {

            $room->save();

            return response()->json(['success' => true]);

        } else {

            // No change detected

            return response()->json(['success' => false, 'message' => 'No change in room number.']);

        }



    } catch (\Exception $e) {

        return response()->json([

            'success' => false,

            'message' => 'Error: ' . $e->getMessage(),

        ], 500);

    }

}



public function getBlocks()

{

    $blocks = Block::select('id', 'name')->get();

    return response()->json($blocks);

}





public function getBeds($room_id)

{

    // Get beds for the selected room that are available (not yet allotted)

    $availableBeds = BedAllocation::where('room_number', $room_id)

        ->whereNull('is_available') // Assuming 'is_available' is null when available

        ->select('id', 'bed_number')

        ->get();



    return response()->json($availableBeds);

}



public function store_participant_room(Request $request)

{



 



 

    // If bed_id is null, find the first available bed in the selected room (unit)

    if ($request->bed_id == null) {

        $availableBed = BedAllocation::where('room_number', $request->unit_id)

            ->where(function ($query) {

                $query->whereNull('is_available');

            })

            ->first();



        if (!$availableBed) {

            return response()->json(['error' => 'No available beds found in the selected room.'], 422);

        }  



        // Inject bed_id into request for validation and further use

        $request->merge(['bed_id' => $availableBed->id]);

    }



    // Server-side validation

    $request->validate([

        'participant_ids' => [

            'required',

            'array',

            'size:1' // ✅ Ensures exactly one participant is selected

        ],

        'participant_ids.0' => 'required|integer|exists:nomination_participants,id',

        'programme_id' => 'required|integer|exists:programmes,id',

        'bed_id' => 'required|integer|exists:bed_allocations,id',

    ]);



    // Proceed with allotment

    $bed = BedAllocation::findOrFail($request->bed_id);

    $participantId = $request->participant_ids[0];



    $bed->participant_id = $participantId;

    $bed->programme_id = $request->programme_id;

    $bed->is_available = false;

    $bed->save();



    // Update room availability

    $room = Room::findOrFail($bed->room_number);



    if ($room->room_type == 1) {

        $room->is_available = false;

        $room->save();

    } else {

        $availableBeds = BedAllocation::where('room_number', $room->id)

            ->where(function ($query) {

                $query->whereNull('is_available')->orWhere('is_available', true);

            })

            ->count();



        if ($availableBeds === 0) {

            $room->is_available = false;

            $room->save();

        }

    }

// Update participant record

$participant=$participant=Participant::where('id', $participantId)->update([

    'bed_id' => $bed->id,

    'rooms_id' => $room->id,

    'hostel_attendence' => 'checkedin',

    'checked_in_at' => now(), // ⏰ Stores current date and time

]);



$participant = Participant::find($participantId);





    Mail::to($participant->email)->send(new AttendanceMarkedPresent($participant));

   

        

    

    return response()->json(['message' => 'Allotment successful']);

}













public function getAllBedTypes()

{

    $types = Typesofroom::all(['id', 'types_of_rooms']);

    return response()->json($types);

}

public function getRoomTypesByBlock(Request $request)

{

    $blockId = $request->block_id;



    // Get distinct room_type IDs from rooms in the selected block

    $roomTypeIds = Room::where('block_id', $blockId)

        ->distinct()

        ->pluck('room_type');



    // Fetch corresponding names from Typesofroom table

    $roomTypes = Typesofroom::whereIn('id', $roomTypeIds)

        ->pluck('types_of_roomS', 'id'); // id = room_type value, types_of_room = label



    return response()->json($roomTypes);

}





 public function toggleStatus($id, Request $request)

{

    $agencyGroup = Room::find($id);



    if (!$agencyGroup) {

        return response()->json(['message' => 'Room not found'], 404);

    }



    // Toggle the status

    $agencyGroup->is_active = $request->status;

    $agencyGroup->save();



    return response()->json(['message' => 'Status updated successfully']);

}

public function getDataType(Request $request)

{

    $types= Typesofroom::select(['id', 'types_of_rooms','avaible_beds']);



    return DataTables::of($types)

        ->make(true);

}


public function getCheckInParticipants(Request $request)
{
    $request->validate([
        'programme_id' => 'required|exists:programmes,id',
    ]);

    // Fetch participants based on the programme_id passed as a parameter

    $participants = Participant::with('bed','bed.room')->where('programme_id', $request->programme_id)

                            ->where('status', 'paid')->get();



    return response()->json($participants);

}

}

