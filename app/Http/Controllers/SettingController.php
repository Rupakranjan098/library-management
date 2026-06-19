<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function store(Request $request)
    {
        $settings = $request->except(['_token']);

        // Checkbox settings that won't be sent if unchecked
        $checkboxes = [
            'maintenance_mode',
            'allow_renewals',
            'notify_member_registration',
            'notify_due_reminder',
            'notify_overdue_notices',
        ];

        foreach ($checkboxes as $key) {
            Setting::set($key, $request->has($key) ? '1' : '0');
        }

        // Standard settings
        foreach ($settings as $key => $value) {
            if (!in_array($key, $checkboxes)) {
                Setting::set($key, $value);
            }
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }
}
