<?php

namespace App\Http\Controllers;

use App\Models\TenantRequest;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenantCredentialsMail;
use App\Mail\TenantRejectionMail;


class TenantRequestController extends Controller
{
    public function index()
    {
        $requests = TenantRequest::latest()->get(); // You can paginate if needed
        return view('tenants.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|unique:tenant_requests,email',
            'plan' => 'nullable|string|in:Basic,Pro',
        ]);

        TenantRequest::create($data);
        return back()->with('success', 'Tenant request submitted.');
    }

    public function approve($id)
    {
        $request = TenantRequest::findOrFail($id);

        if ($request->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        // Step 1: Create Tenant
        try {
            // Use domain as the tenant id or use UUID
            $tenant = Tenant::create([
                'id' => (string) Str::uuid(),  // or use $request->domain for subdomain-style tenant IDs
                'name' => $request->username,
                'email' => $request->email,  
                'organization' => $request->organization,
                'domain' => $request->domain,
                'address' => $request->address,
                'plan' => $request->plan,
                'status' => 'active',
            ]);

            // Step 2: Create domain record for tenant
            $tenant->domains()->create([
                'domain' => $request->domain . '.' . config('app.domain'), // Ensure this is correct in your config
            ]);

            // Step 3: Switch to tenant context
            tenancy()->initialize($tenant);

            // Step 4: Create User for Tenant
            $password = Str::random(10); // Generate a random password

            $user = \App\Models\User::create([
                'name' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($password),
                'tenant_id' => $tenant->id, // Ensure tenant_id is stored correctly
                'role' => 'admin', // You can hardcode or adjust based on your needs
            ]);

            // Step 5: Assign Role (if using roles manually)
            // If you have a role-based system like Spatie, you can assign the role
            // $user->assignRole('admin'); // Uncomment if using Spatie

            // Step 6: Send email to the new user with credentials
            Mail::to($user->email)->send(new TenantCredentialsMail($user, $password));

            // Step 7: End tenant context
            tenancy()->end();

            // Step 8: Update request status to approved
            $request->update(['status' => 'active']);

            return back()->with('success', 'Tenant approved and user created.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error approving tenant: ' . $e->getMessage());
        }
    }
    public function reject($id)
    {
        $request = TenantRequest::findOrFail($id);
        
        if ($request->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }
    
        $request->update(['status' => 'rejected']);
    
        // Send rejection email
        try {
            Mail::to($request->email)->send(new TenantRejectionMail($request));
        } catch (\Exception $e) {
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }
    
        return back()->with('success', 'Tenant request rejected and user notified.');
    }

public function update(Request $request, $id)
{
    $request->validate([
        'username' => 'required|string|max:255',
        'organization' => 'required|string|max:255',
        'domain' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'email' => 'required|email',
        'plan' => 'required|string',
    ]);

    $tenantRequest = TenantRequest::findOrFail($id);
    $tenantRequest->update($request->all());

    // Sync plan with related tenant
    $tenant = Tenant::where('email', $tenantRequest->email)->first();
    if ($tenant) {
        $tenant->update([
            'plan' => $request->input('plan'),
        ]);
    }

    return redirect()->route('tenants.index')->with('success', 'Tenant request updated successfully!');
}


    public function destroy($id)
{
    $request = TenantRequest::findOrFail($id);

    // Try to find the corresponding Tenant
    $tenant = Tenant::where('email', $request->email)->first();

    if ($tenant) {
        $tenant->delete(); // Stancl will also drop the database and domains if configured
    }

    $request->delete();

    return back()->with('success', 'Tenant request and tenant deleted.');
}
public function disable($id)
{
    $request = TenantRequest::findOrFail($id);

    if ($request->status === 'active') {
        // Update the TenantRequest status to 'disabled'
        $request->status = 'disabled';
        $request->save();

        // Also update the actual tenant's status column
        $tenant = Tenant::where('email', $request->email)->first();
        if ($tenant) {
            // Update the tenant's 'status' column to 'disabled'
            $tenant->status = 'disabled';
            $tenant->save();
        }

        return redirect()->back()->with('success', 'Tenant domain has been disabled.');
    }

    return redirect()->back()->with('error', 'This tenant is not active.');
}


public function enable($id)
{
    // Retrieve the tenant request by ID
    $request = TenantRequest::findOrFail($id);

    // Find the corresponding tenant by email
    $tenant = Tenant::where('email', $request->email)->first();

    if ($tenant && $request->status === 'disabled') {
        // Update the TenantRequest status to 'active'
        $request->status = 'active';
        $request->save();

        // Also update the tenant's 'status' column to 'active'
        $tenant->status = 'active';
        $tenant->save();

        // Redirect with success message
        return redirect()->route('tenants.index')->with('success', 'Tenant has been enabled.');
    }

    // If the tenant is not disabled or no tenant found, return with an error
    return redirect()->route('tenants.index')->with('error', 'This tenant cannot be enabled.');
}


}
