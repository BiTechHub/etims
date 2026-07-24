<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class AddUserController extends Controller
{
   
    // protected $modules = [
    //     'dashboard' => 'Dashboard',
    //     'static_dashboard' => 'Static Dashboard',
    //     'add_faculty' => 'Add Faculty',
    //     'master' => 'Master',
    //     'programme' => 'Programme',
    //     'question_management' => 'Question Management',
    //     'nomination' => 'Nomination',
    //     'budget' => 'Budget',
    //     'reports' => 'Reports',
    // ];

    protected $modules = [
    'dashboard' => 'Dashboard',
    'static_dashboard' => 'Static Dashboard',
    'add_faculty' => 'Add Faculty',
    'master' => 'Master',
    'programme' => 'Programme',
    'question_management' => 'Question Management',
    'nomination' => 'Nomination',
    'budget' => 'Budget',
    'reports' => 'Reports',
    'registration' => 'Registration List',
    'payment' => 'Payments',

     // Hostel-specific modules
    'hostel_master'    => 'Hostel Master (Blocks/Rooms)',
    'hostel_checkout'  => 'Hostel Checkouts',
    'room_allotment'   => 'Room Allotment',
];

    protected $actions = ['view', 'add', 'edit', 'delete'];

    public function index()
    {
        $userTypes = [
            'User',
            'Hostel',
        ];

        return view('admin.user.adduser', compact('userTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_type' => 'required|string',
            'username' => 'required|string|unique:admins,user_name',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Admin::create([
            'role' => $request->user_type,
            'user_name' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function getData(Request $request)
    {
        $users = Admin::select([
            'id',
            'name',
            'role',
            'user_name',
            'email',
        ]);

        return DataTables::of($users)
            ->addColumn('user_type', function ($row) {
                return $row->role;
            })
            ->addColumn('username', function ($row) {
                return $row->user_name;
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-warning edit-btn"
                            data-id="'.$row->id.'"
                            data-user_type="'.htmlspecialchars($row->role).'"
                            data-username="'.htmlspecialchars($row->user_name).'"
                            data-name="'.htmlspecialchars($row->name).'"
                            data-email="'.htmlspecialchars($row->email).'">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">
                            <i class="fas fa-trash"></i>
                        </button>
                        <a href="'.route('user.adduser.permissions.page', $row->id).'" class="btn btn-sm btn-info">
                        <i class="fas fa-key"></i> Access
                          </a>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function update(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        $request->validate([
            'user_type' => 'required|string',
            'username' => 'required|string|unique:admins,user_name,'.$id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,'.$id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->role = $request->user_type;
        $user->user_name = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = Admin::findOrFail($id);

        if (auth('admin')->id() == $id) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }

    /**
     * Return this admin's current permissions (for modal fill)
     */
    public function getPermissions($id)
    {
        $user = Admin::findOrFail($id);

        return response()->json([
            'modules' => $this->modules,
            'actions' => $this->actions,
            'permissions' => $user->permissions ?? [],
        ]);
    }

    /**
     * Save permissions submitted from the modal
     */
    public function updatePermissions(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        // Expecting: permissions[module][action] = 1/0
        $submitted = $request->input('permissions', []);

        $data = [];
        foreach ($this->modules as $key => $label) {
            foreach ($this->actions as $action) {
                $data[$key][$action] = isset($submitted[$key][$action]) ? 1 : 0;
            }
        }

        $user->permissions = $data;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Access updated successfully.']);
    }

    public function showPermissionsPage($id)
    {
        $user = Admin::findOrFail($id);

        return view('admin.user.permissions', [
            'user' => $user,
            'modules' => $this->modules,
            'actions' => $this->actions,
        ]);
    }
}
