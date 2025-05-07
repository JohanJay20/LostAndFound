<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CustomizeController extends Controller
{
    public function index()
    {
        $tenant = tenant();  // Get the current tenant
        $customizeData = $tenant->customize_data ?? [];  // Safely get the stored customize_data
        $font = $customizeData['font'] ?? null;
        $sidebarColor = $customizeData['sidebar_color'] ?? '#f4f5f7';  // Default sidebar color
        $navbarColor = $customizeData['navbar_color'] ?? '#f4f5f7';  // Default navbar color

        $logo = isset($customizeData['logo']) ? asset('storage/logos/' . basename($customizeData['logo'])) : asset('images/logo.svg');
        $logoMini = isset($customizeData['logo_mini']) ? asset('storage/logos/' . basename($customizeData['logo_mini'])) : asset('images/logo-mini.svg');

        return view('tenant.customize.index', compact('font', 'sidebarColor', 'navbarColor', 'logo', 'logoMini'));
    }

    public function update(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'font' => 'required|string|max:255',
            'sidebar_color' => 'required|string|max:7',  // Ensure valid color hex code (e.g., #343a40)
            'navbar_color' => 'required|string|max:7',   // Ensure valid color hex code for navbar
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // Logo validation
            'logo_mini' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // Mini logo validation
        ]);

        $tenant = tenant();

        // Get the current customize_data, or initialize an empty array if null
        $currentCustomizeData = $tenant->customize_data ?? [];

        // Log the start of the customization update process
        Log::info('Customization update started for tenant: ' . $tenant->id);

        // Handle logo upload (store it in public/storage/logos)
        if ($request->hasFile('logo')) {
            // Delete the old logo if it exists
            if (!empty($currentCustomizeData['logo'])) {
                $oldLogoPath = 'public/storage/logos/' . basename($currentCustomizeData['logo']);
                if (Storage::exists($oldLogoPath)) {
                    Storage::delete($oldLogoPath);
                    Log::info('Old logo deleted for tenant: ' . $tenant->id);
                }
            }

            // Store the new logo directly in public/storage/logos
            $logoPath = $request->file('logo')->storeAs('public/storage/logos', $request->file('logo')->getClientOriginalName());
            $currentCustomizeData['logo'] = 'storage/logos/' . $request->file('logo')->getClientOriginalName();

            // Log logo upload success
            Log::info('Logo uploaded successfully for tenant: ' . $tenant->id . '. Path: ' . $logoPath);
        } else {
            // Log if no logo was uploaded
            Log::info('No logo uploaded for tenant: ' . $tenant->id);
        }

        // Handle mini logo upload (store it in public/storage/logos)
        if ($request->hasFile('logo_mini')) {
            // Delete the old mini logo if it exists
            if (!empty($currentCustomizeData['logo_mini'])) {
                $oldMiniLogoPath = 'public/storage/logos/' . basename($currentCustomizeData['logo_mini']);
                if (Storage::exists($oldMiniLogoPath)) {
                    Storage::delete($oldMiniLogoPath);
                    Log::info('Old mini logo deleted for tenant: ' . $tenant->id);
                }
            }

            // Store the new mini logo directly in public/storage/logos
            $logoMiniPath = $request->file('logo_mini')->storeAs('public/storage/logos', $request->file('logo_mini')->getClientOriginalName());
            $currentCustomizeData['logo_mini'] = 'storage/logos/' . $request->file('logo_mini')->getClientOriginalName();

            // Log mini logo upload success
            Log::info('Mini logo uploaded successfully for tenant: ' . $tenant->id . '. Path: ' . $logoMiniPath);
        } else {
            // Log if no mini logo was uploaded
            Log::info('No mini logo uploaded for tenant: ' . $tenant->id);
        }

        // Merge new font, sidebar color, and navbar color into customize_data
        $currentCustomizeData['font'] = $validated['font'];
        $currentCustomizeData['sidebar_color'] = $validated['sidebar_color'];
        $currentCustomizeData['navbar_color'] = $validated['navbar_color'];

        // Assign the updated array back to the tenant's customize_data
        $tenant->customize_data = $currentCustomizeData;

        // Save the tenant instance
        $tenant->save();

        // Log successful update
        Log::info('Customization updated successfully for tenant: ' . $tenant->id);

        // Redirect back with success message
        return redirect()->route('customize.index')->with('success', 'Customization updated successfully!');
    }
    public function reset(Request $request)
{
    $tenant = tenant();  // Get the current tenant

    // Define the default values
    $defaultCustomizeData = [
        'font' => 'Arial',  // Default font
        'sidebar_color' => '#f4f5f7',  // Default sidebar color
        'navbar_color' => '#f4f5f7',  // Default navbar color
        'logo' => null,  // Default logo (no custom logo)
        'logo_mini' => null,  // Default mini logo (no custom mini logo)
    ];

    // Reset the customization data to the default values
    $tenant->customize_data = $defaultCustomizeData;

    // Save the tenant instance with the reset customization data
    $tenant->save();

    // Optionally, delete any custom logos that were uploaded
    if (!empty($tenant->customize_data['logo'])) {
        $oldLogoPath = 'public/storage/logos/' . basename($tenant->customize_data['logo']);
        if (Storage::exists($oldLogoPath)) {
            Storage::delete($oldLogoPath);
        }
    }

    if (!empty($tenant->customize_data['logo_mini'])) {
        $oldMiniLogoPath = 'public/storage/logos/' . basename($tenant->customize_data['logo_mini']);
        if (Storage::exists($oldMiniLogoPath)) {
            Storage::delete($oldMiniLogoPath);
        }
    }

    // Log the reset action
    Log::info('Customization reset to default for tenant: ' . $tenant->id);

    // Redirect back with success message
    return redirect()->route('customize.index')->with('success', 'Customization has been reset to default values!');
}

}
