<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Models\company\Company;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    
    public function generalSettings(Request $request)
    {
        $company = Company::findOrFail(auth()->user()->ins);
        if ($request->post()) {
            $input = $request->except('_token');
            $company->update($input);
            return redirect()->back();
        }
        return view('settings.general', compact('company'));
    } 
    
    /**
     * Clear Cache
     */
    public function clearCache() 
    {   
        try {
            Artisan::call('cache:clear');
            Artisan::call('route:cache');
            Artisan::call('config:cache');
            return "Application cache cleared";
        } catch (\Throwable $th) {
            return "Something went wrong! " . $th->getMessage();
        }
    }

    /**
     * Maintenance Mode
     */
    public function siteDown() 
    {
        Artisan::call('down');
        return redirect()->back();
    }
}
