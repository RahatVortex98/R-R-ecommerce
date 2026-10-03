<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    const FIELDS = [
        'site_name' => 'Site name', 'tagline' => 'Tagline', 'announcement' => 'Top announcement bar',
        'phone' => 'Phone', 'whatsapp' => 'WhatsApp number (with country code, e.g. 8801XXXXXXXXX)', 'email' => 'Email',
        'address' => 'Address', 'facebook' => 'Facebook URL', 'instagram' => 'Instagram URL',
        'bkash_number' => 'bKash merchant/personal number', 'shipping_inside_dhaka' => 'Shipping inside Dhaka (৳)',
        'shipping_outside_dhaka' => 'Shipping outside Dhaka (৳)', 'free_shipping_over' => 'Free shipping over (৳, 0 = off)',
    ];
    public function edit() { return view('admin.settings', ['fields' => self::FIELDS]); }
    public function update(Request $r)
    {
        Setting::put($r->only(array_keys(self::FIELDS)));
        return back()->with('ok', 'Settings saved.');
    }
}