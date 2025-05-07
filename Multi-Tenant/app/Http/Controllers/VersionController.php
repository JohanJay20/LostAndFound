<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class VersionController extends Controller
{
    public function update(Request $request)
    {
        Artisan::call('version:fetch');

        return redirect()->back()->with('status', 'Application version updated successfully!');
    }
}
