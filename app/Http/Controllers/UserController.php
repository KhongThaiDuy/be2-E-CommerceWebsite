<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->keyword . '%')
                ->orWhere('email', 'like', '%' . $request->keyword . '%')
                ->orWhere('full_name', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $sortOrder = $request->get('sort', 'asc');
        $users = $query->orderBy('id', $sortOrder)->paginate(10)->withQueryString();

        

        return view('admin.users.index', compact('users'));
    }


    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'email' => 'nullable|email|unique:users',
            'full_name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable|digits:10',
            'role' => 'required|in:customer,admin',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Example image validation
        ]);

        $user = new User($request->except('password', 'password_confirmation', 'image'));
        $user->password = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('users', 'public'); // Store in storage/app/public/users
            $user->image = $imagePath;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        //dd($user->id, $user->hash_id);
        
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'full_name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable|digits:10',
            'role' => 'required|in:customer,admin',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Example image validation
        ]);

        $userData = $request->except('password', 'password_confirmation', 'image');
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('assets/images');
            $file->move($destination, $filename);
        
            $user->image = 'assets/images/' . $filename;
        }
        

        $user->update($userData);

        return redirect()->route('user.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Consider deleting the user's image if it exists
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully!');
    }
}