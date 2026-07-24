<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use App\Models\AgencyGroup;

use App\Models\User;

use App\Models\UserType;

use Illuminate\Auth\Events\Validated;

use Illuminate\Http\Request;  

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Str;

class UserController extends Controller

{     

    public function index(){

       $userTypes = [
           'Faculty',
           'Others',
       ];
        return view('admin.user.user_manage',compact('userTypes'));

    }


    public function store(Request $request)

    {

       

        // Validate the request data

        $request->validate([

            'user_type'   => 'required|string',

            'username'    => 'required|string|unique:users,username',

            'name'        => 'required|string|max:255',

            'email'       => 'required|email|unique:users,email',

            'password'    => 'required|string|min:6|confirmed',

            'phone'       => 'nullable|string|max:20',

            'dob'         => 'nullable|date',

            'designation' => 'nullable|string|max:255',

            'address'     => 'nullable|string',

            'state'       => 'nullable|string|max:100',

            'city'        => 'nullable|string|max:100',

            'pincode'     => 'nullable|string|max:10',

            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'hindi_name'  =>'required|string|max:255',

        ]);

    

        // Handle image upload if present

        $imagePath = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();

            $imagePath = $image->storeAs('uploads/users', $imageName, 'public');

        }

    

        // Store user

        User::create([

            'user_type'   => $request->user_type,

            'username'    => $request->username,

            'name'        => $request->name,

            'email'       => $request->email,

            'password'    => Hash::make($request->password),

            'phone'       => $request->phone,

            'dob'         => $request->dob,

            'designation' => $request->designation,

            'address'     => $request->address,

            'state'       => $request->state,

            'city'        => $request->city,

            'pincode'     => $request->pincode,

            'image'       => $imagePath,

           'hindi_name'   =>$request->hindi_name

        ]);

    

        return redirect()->back()->with('success', 'User created successfully.');

    }

    
 // Fetch user data for DataTable

 public function getData(Request $request)

 {

     $agencyTypes = User::select([   'user_type',

     'username',

     'name',

     'email',

     'dob',

     'designation',

     'address',

     'state',

     'city',

     'pincode',

     'phone',

     'image',

     'password',

     'hindi_name',

      'is_active',

    'id'])->where('deleted_at',0);

 

     return DataTables::of($agencyTypes)

         ->make(true);

 }

 
 public function destroy($id)

{

 if (!auth('admin')->user()->hasAccess('user', 'delete')) {
        return response()->json(['success' => false, 'message' => 'You do not have permission to delete.'], 403);
    }

    $user = User::findOrFail($id);



    // Optionally delete associated image from storage

    if ($user->image && \Storage::disk('public')->exists($user->image)) {

        \Storage::disk('public')->delete($user->image);

    }



    // Custom soft delete logic (flag-based)

    $user->update([

        'deleted_at' => 1, // your custom "deleted" flag

    ]);



    return response()->json(['success' => true, 'message' => 'User deleted successfully.']);

}

public function update(Request $request, $id)

{
     if (!auth('admin')->user()->hasAccess('user', 'edit')) {
        abort(403, 'You do not have permission to edit users.');
    }

    $user = User::findOrFail($id);



    // Validate the request data

    $request->validate([

        'user_type'   => 'required|string',

        'username'    => 'required|string|unique:users,username,' . $id,

        'name'        => 'required|string|max:255',

        'hindi_name'        => 'required|string|max:255',

        'email'       => 'required|email|unique:users,email,' . $id,

        'password'    => 'nullable|string|min:6',

        'phone'       => 'nullable|string|max:20',

        'dob'         => 'nullable|date',

        'designation' => 'nullable|string|max:255',

        'address'     => 'nullable|string',

        'state'       => 'nullable|string|max:100',

        'city'        => 'nullable|string|max:100',

        'pincode'     => 'nullable|string|max:10',

        'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

    ]);



    // Handle image upload if present

    if ($request->hasFile('image')) {

        // Optionally delete old image

        if ($user->image && \Storage::disk('public')->exists($user->image)) {

            \Storage::disk('public')->delete($user->image);

        }



        $image = $request->file('image');

        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();

        $imagePath = $image->storeAs('uploads/users', $imageName, 'public');

        $user->image = $imagePath;

    }



    // Update user fields

    $user->user_type   = $request->user_type;

    $user->username    = $request->username;

    $user->name        = $request->name;

    $user->hindi_name        = $request->hindi_name;

    $user->email       = $request->email;

    $user->phone       = $request->phone;

    $user->dob         = $request->dob;

    $user->designation = $request->designation;

    $user->address     = $request->address;

    $user->state       = $request->state;

    $user->city        = $request->city;

    $user->pincode     = $request->pincode;



    // Only update password if provided

    if ($request->filled('password')) {

        $user->password = Hash::make($request->password);

    }



    $user->save();



    return redirect()->back()->with('success', 'User updated successfully.');

}





   public function toggleStatus(Request $request, $id)

    {

       if (!auth('admin')->user()->hasAccess('user', 'edit')) {
        return response()->json(['message' => 'You do not have permission to perform this action.'], 403);
    }

        $agency = User::findOrFail($id);

        $agency->is_active = $request->status;

        $agency->save();

    

        return response()->json(['message' => 'Status updated successfully!']);

    }



}

