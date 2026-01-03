<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Role;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        try {
            if (!auth()->user()->hasRole('admin')) {
                abort(403, 'Unauthorized action.');
            }

            $users = User::with('roles')->paginate(10);
            return view('users.index', compact('users'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load users: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            if (!auth()->user()->hasRole('admin')) {
                abort(403, 'Unauthorized action.');
            }

            $roles = Role::all();
            return view('users.create', compact('roles'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load create form: ' . $e->getMessage());
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            $user->assignRole($validated['role']);

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        try {
            if (!auth()->user()->hasRole('admin')) {
                abort(403, 'Unauthorized action.');
            }

            $user->load('roles');
            return view('users.show', compact('user'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load user: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        try {
            if (!auth()->user()->hasRole('admin')) {
                abort(403, 'Unauthorized action.');
            }

            $roles = Role::all();
            $user->load('roles');
            return view('users.edit', compact('user', 'roles'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $validated = $request->validated();

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = $validated['password'];
            }

            $user->update($updateData);
            $user->syncRoles([$validated['role']]);

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            if (!auth()->user()->hasRole('admin')) {
                abort(403, 'Unauthorized action.');
            }

            if ($user->id === auth()->id()) {
                return redirect()->route('users.index')
                    ->with('error', 'You cannot delete yourself.');
            }

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
