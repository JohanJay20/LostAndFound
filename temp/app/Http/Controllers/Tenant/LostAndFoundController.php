<?php

namespace App\Http\Controllers\Tenant;

use App\Models\LostAndFound;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Claimer;

class LostAndFoundController extends Controller
{
    // Display a list of lost and found items
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $items = LostAndFound::with('claimer') // <- Eager load
            ->when($search, function ($query) use ($search) {
                return $query->where('item_name', 'like', "%$search%")
                             ->orWhere('description', 'like', "%$search%")
                             ->orWhere('found_at', 'like', "%$search%")
                             ->orWhere('status', 'like', "%$search%")
                             ->orWhere('location', 'like', "%$search%")
                             ->orWhere('category', 'like', "%$search%");
            })
            ->paginate(10);
    
        return view('tenant.lostandfound.index', compact('items'));
    }
    

    // Show the form to create a new Lost and Found Item
    public function create()
    {
        return view('tenant.lostandfound.create');
    }

    // Store a newly created Lost and Found Item
    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'found_at' => 'required|date',
            'status' => 'required|string|max:50',  // Validate status
            'location' => 'required|string|max:255', // Validate location
            'category' => 'required|string|max:255', // Validate category
        ]);

        // Create a new Lost and Found item
        LostAndFound::create([
            'item_name' => $request->item_name,
            'description' => $request->description,
            'found_at' => $request->found_at,
            'status' => $request->status,  // Save status
            'location' => $request->location,  // Save location
            'category' => $request->category,  // Save category
        ]);

        return redirect()->route('lostandfound.index')->with('success', 'Item created successfully!');
    }

    // Show the form to edit a specific Lost and Found Item
    public function edit(LostAndFound $lostAndFound)
    {
        return view('tenant.lostandfound.edit', compact('lostAndFound'));
    }

    // Update the specified Lost and Found Item
    public function update(Request $request, LostAndFound $lostAndFound)
    {
        // Validate incoming data
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'found_at' => 'required|date',
            'status' => 'required|string|max:50',  // Validate status
            'location' => 'required|string|max:255', // Validate location
            'category' => 'required|string|max:255', // Validate category
        ]);

        // Update the Lost and Found item
        $lostAndFound->update([
            'item_name' => $request->item_name,
            'description' => $request->description,
            'found_at' => $request->found_at,
            'status' => $request->status,  // Update status
            'location' => $request->location,  // Update location
            'category' => $request->category,  // Update category
        ]);

        return redirect()->route('lostandfound.index')->with('success', 'Item updated successfully!');
    }

    // Delete the specified Lost and Found Item
    public function destroy(LostAndFound $lostAndFound)
    {
        // Delete the Lost and Found item
        $lostAndFound->delete();

        return redirect()->route('lostandfound.index')->with('success', 'Item deleted successfully!');
    }
    public function claim(Request $request, LostAndFound $lostAndFound)
{
    // Validate claimer information
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'contact_number' => 'required|string|max:20',
        'email' => 'nullable|email',
    ]);

    // Update the lost and found item's status to "claimed"
    $lostAndFound->update([
        'status' => 'claimed',
    ]);

    // Store claimer details
    $lostAndFound->claimer()->create([
        'name' => $validated['name'],
        'contact_number' => $validated['contact_number'],
        'email' => $validated['email'],
    ]);

    return redirect()->route('lostandfound.index')->with('success', 'Item successfully claimed.');
}
}
