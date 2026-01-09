<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show all users (admin + customer + others)
     */
    public function index()
    {
        $users = User::query()
            ->orderByDesc('id')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to create ADMIN (your existing behavior)
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store ADMIN (role forced)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Admin created successfully');
    }

    /**
     * Edit user info (name/email/role)
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user info
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,customer,user',
        ]);

        // Optional safety: prevent changing your own role away from admin
        if (auth()->id() === $user->id && $request->role !== 'admin') {
            return back()->with('error', 'You cannot remove your own admin role.');
        }

        // Optional safety: prevent demoting the last admin
        if ($user->role === 'admin' && $request->role !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'You cannot demote the last admin.');
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Update password (works for any user)
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Delete user (works for any user)
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Optional: prevent deleting last admin
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'You cannot delete the last admin account.');
            }
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
