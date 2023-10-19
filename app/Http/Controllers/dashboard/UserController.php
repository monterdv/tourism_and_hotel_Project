<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\UserStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function index(Request $request)
    {
        $users = User::join('users_status', 'users.status_id', '=', 'users_status.id')
            ->join('departments', 'users.department_id', '=', 'departments.id')
            ->select('users.*', 'users_status.name as status', 'departments.name as department')
            ->get();
        // ->paginate(10);
        // $query = User::join('users_status', 'users.status_id', '=', 'users_status.id')
        // ->join('departments', 'users.department_id', '=', 'departments.id')
        // ->select('users.*', 'users_status.name as status', 'departments.name as department');

        $users_status = UserStatus::select('id as value', 'name as label')->get();
        $Department = Department::where('id', '<>', 1)
            ->select('id as value', 'name as label')
            ->get();


        $data = [
            "users" => $users,
            "users_status" => $users_status,
            "Department" => $Department,
        ];

        return response()->json($data);
    }

    public function sreach(Request $request)
    {
        // return $request;
        // Get the search parameters from the request
        $name = $request->sreachName;
        $department = $request->sreachDepartment;
        $status = $request->sreachStatus;

        // Start building the query
        $query = User::join('users_status', 'users.status_id', '=', 'users_status.id')
            ->join('departments', 'users.department_id', '=', 'departments.id')
            ->select('users.*', 'users_status.name as status', 'departments.name as department');

        // Apply filters based on search parameters
        if ($name) {
            $query->where('users.name', 'like', '%' . $name . '%');
        }
        if ($department) {
            $query->where('users.department_id', $department);
        }
        if ($status) {
            $query->where('users.status_id', $status);
        }

        // Execute the query and retrieve the results
        $users = $query->get();

        $data = [
            "users" => $users,
        ];

        return response()->json($data);
    }


    public function create()
    {
        $users_status = UserStatus::select('id as value', 'name as label')->get();
        $Department = Department::where('id', '<>', 1)
            ->select('id as value', 'name as label')
            ->get();

        return response()->json([
            'users_status' => $users_status,
            'Department' => $Department,
        ]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'department_id' => 'required',
            'status_id' => 'required',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'password_confirmation.required' => 'Password confirmation is required',
            'department_id.required' => 'Department is required',
            'status_id.required' => 'Status is required',
        ]);

        if ($request->password != $request->password_confirmation) {
            return response()->json([
                'errors' => [
                    'password_confirmation' => ['Password confirmation does not match'],
                ]
            ], 422);
        }

        // Handle file upload
        if ($request->hasFile('avatar')) {
            $uploadedFile = $request->file('avatar');
            $destinationPath = public_path('assets/img');

            $newFileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();

            $uploadedFile->move($destinationPath, $newFileName);

            $avatarPath = '/assets/img/' . $newFileName;
            $data['avatar'] = $avatarPath;
        } else {
            $data['avatar'] = null;
        }

        // Create the user
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => $data['avatar'],
            'department_id' => $data['department_id'],
            'status_id' => $data['status_id'],
        ]);

        return response()->json(['message' => 'User created successfully']);
    }

    public function edit($id)
    {
        $users_status = UserStatus::select('id as value', 'name as label')->get();
        $Department = Department::where('id', '<>', 1)
            ->select('id as value', 'name as label')
            ->get();

        $users = User::find($id);

        return response()->json([
            'users' => $users,
            'users_status' => $users_status,
            'Department' => $Department,
        ]);
    }

    public function update(Request $request, $id)
    {
        User::find($id)->update([
            'department_id' => $request['department_id'],
            'status_id' => $request['status_id']
        ]);
        return response()->json(['message' => 'User update successfully']);
    }
}
