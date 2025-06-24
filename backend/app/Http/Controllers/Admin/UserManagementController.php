<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Controllers\Controller;

use App\Http\Requests\UpdateUserRequest;

class UserManagementController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return $this->successResponse(
            $users,
            'Users retrieved successfully.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = auth()->user();

        $payload['id'] = $user->id;
        $payload['name'] = $user->name;
        $payload['email'] = $user->email;
        $payload['role'] = $user->role;

        return $this->successResponse(
            $payload,
            'User retrieved successfully.',
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request)
    {
        try {
            $user = auth()->user();
            
            $payload = $request->only(['name', 'email']);
    
            // Only update password if provided
            if ($request->filled('password')) {
                $payload['password'] = Hash::make($request->password);
            }
    
            // Update user
            $user->update($payload);
    
            // Return response without password
            return $this->successResponse(
                $user,
                'Profile updated successfully.',
                200
            );
    
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update profile.',
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->successResponse(
            null,
            'User deleted successfully.',
            200
        );
    }
}
