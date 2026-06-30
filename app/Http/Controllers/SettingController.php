<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrNew();
        // return $setting;
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::firstOrNew();

        $data = $request->except(['site_logo', 'site_favicon']);
        $data['maintenance_mode'] = $request->has('maintenance_mode') ? 1 : 0;

        // return $request->all();

        if ($request->hasFile('site_logo')) {
            // Delete old logo
            if ($setting->site_logo) {
                 $path = public_path('uploads/logo/' . $setting->site_logo);

                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            $file = $request->file('site_logo');

            $filename = time() . '_logo.' . $file->getClientOriginalExtension();

            $destinationPath =  public_path('uploads/logo');

            // Create directory if it doesn't exist
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Move file
            $file->move($destinationPath, $filename);

            $data['site_logo'] =  $filename;
        }
        if ($request->hasFile('site_favicon')) {

            if ($setting->site_favicon) {
                 $path = public_path('uploads/logo/' . $setting->site_favicon);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            $file = $request->file('site_favicon');

            $filename = time() . '_logo.' . $file->getClientOriginalExtension();

            $destinationPath =  public_path('uploads/logo');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Move file
            $file->move($destinationPath, $filename);

            $data['site_favicon'] =  $filename;
        }

        $setting->fill($data)->save();

        return back()->with('message', 'Settings Updated Successfully')->with('status', 'success');
    }
}
