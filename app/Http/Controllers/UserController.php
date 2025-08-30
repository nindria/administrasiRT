<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('User/Index', [
            "users" => User::with("roles")->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('User/Create', [
            "roles" => Role::select("id", "name")->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8",
            "roles" => "required|array"
        ]);

        $user = User::create([
            "name" => $validated['name'],
            "email" => $validated['email'],
            "password" => Hash::make($validated['password'])
        ]);
        
        // Get role names from the submitted role IDs
        $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
        $user->syncRoles($roleNames);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return Inertia::render("User/Show", [
            "user" => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        
        return Inertia::render("User/Edit", [
            "user" => $user,
            "roles" => Role::select("id", "name")->get(),
            "userRoles" => $user->roles()->pluck('id')->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email," . $id,
            "password" => "nullable|min:8"
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update roles if provided
        if ($request->has('roles')) {
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        }

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
