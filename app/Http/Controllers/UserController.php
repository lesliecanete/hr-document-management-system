<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,hr_manager,hr_staff',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,hr_manager,hr_staff',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    
    public function destroy(User $user)
    {
        try {
            // Prevent users from deleting themselves
            if ($user->id == auth()->id()) {
                return redirect()->route('users.index')
                    ->with('error', 'You cannot delete your own account while logged in.');
            }

            // Prevent deletion of the last admin
            if ($user->role == 'admin') {
                $adminCount = User::where('role', 'admin')->count();
                if ($adminCount <= 1) {
                    return redirect()->route('users.index')
                        ->with('error', 'Cannot delete the last administrator account. System requires at least one admin.');
                }
            }

            // Store user info for success message
            $userName = $user->name;
            $userEmail = $user->email;

            // Delete the user
            $user->delete();

            // Optional: Log the deletion activity
            \Log::info("User deleted: {$userName} ({$userEmail}) by user ID: " . auth()->id());

            return redirect()->route('users.index')
                ->with('success', "User '{$userName}' has been permanently deleted.");

        } catch (\Exception $e) {
            \Log::error('User deletion failed: ' . $e->getMessage());
            
            return redirect()->route('users.index')
                ->with('error', 'Failed to delete user. Please try again.');
        }
    }
}