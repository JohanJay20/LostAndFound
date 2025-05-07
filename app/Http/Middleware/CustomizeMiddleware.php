<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomizeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Get the current tenant
        $tenant = tenant();
    
        // Get the customization data (font, colors, etc.)
        $customizeData = $tenant->customize_data ?? [];
    
        // Share the customization data globally with all views
        view()->share('customize_data', $customizeData);
    
        // Extract font, sidebar color, and navbar color for easy access in your views
        $font = $customizeData['font'] ?? null;
        $sidebarColor = $customizeData['sidebar_color'] ?? '#f4f5f7';  // Default sidebar color
        $navbarColor = $customizeData['navbar_color'] ?? '#f4f5f7';  // Default navbar color
    
        // Check if a custom logo exists, otherwise use the default one
        $logo = isset($customizeData['logo']) 
            ? asset('storage/logos/' . basename($customizeData['logo'])) 
            : asset('images/logo.svg'); // Default logo if no custom logo
    
        $logoMini = isset($customizeData['logo_mini']) 
            ? asset('storage/logos/' . basename($customizeData['logo_mini'])) 
            : asset('images/logo-mini.svg'); // Default mini logo if no custom mini logo
    
        // Debugging: Check the paths of logos
        \Log::debug("Logo Path: " . $logo);
        \Log::debug("Logo Mini Path: " . $logoMini);
    
        // Share the data with views
        view()->share('logo', $logo);
        view()->share('logoMini', $logoMini);
        view()->share('font', $font);
        view()->share('sidebarColor', $sidebarColor);
        view()->share('navbarColor', $navbarColor);
    
        // Pass the request further into the application
        return $next($request);
    }
    
}
