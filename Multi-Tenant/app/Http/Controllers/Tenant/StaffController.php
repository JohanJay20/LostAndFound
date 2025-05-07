<?php

namespace App\Http\Controllers\Tenant;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    // Staff Index
    public function index(Request $request)
    {
        $query = User::where('role', 'staff');
    
        // Check if there's a search query
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            // You can add more conditions based on the fields you want to search by
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
    
        // Retrieve staff with the applied search filter
        $staff = $query->get();
    
        return view('tenant.staff.index', compact('staff'));
    }
    
    // Create Staff
    public function create()
    {
        return view('tenant.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'staff',
        ]);
    
        return redirect()->route('staff.index');
    }
    
    // Show Staff
    public function show(User $staff)
    {
        return view('tenant.staff.show', compact('staff'));
    }

    // Edit Staff
    public function edit(User $staff)
    {
        return view('tenant.staff.edit', compact('staff'));
    }

    // Update Staff
    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);
    
        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $staff->password,
        ]);
    
        return redirect()->route('staff.index');
    }
    

    // Delete Staff
    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index');
    }
}
