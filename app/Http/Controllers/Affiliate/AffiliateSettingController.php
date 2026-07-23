<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Services\AffiliateSettingsService;
use Illuminate\Http\Request;

class AffiliateSettingController extends Controller
{
    public function __construct(private AffiliateSettingsService $settings) {}

    public function edit()
    {
        $values = $this->settings->all();

        return view('admin.affiliates.settings', compact('values'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'default_commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'cookie_duration_days'    => ['required', 'integer', 'min:1', 'max:365'],
            'refund_hold_days'        => ['required', 'integer', 'min:0', 'max:180'],
            'min_payout_amount'       => ['required', 'numeric', 'min:0'],
            'auto_approve_affiliates' => ['nullable', 'boolean'],
            'self_referral_block'     => ['nullable', 'boolean'],
        ]);

        $validated['auto_approve_affiliates'] = $request->boolean('auto_approve_affiliates');
        $validated['self_referral_block']     = $request->boolean('self_referral_block');

        foreach ($validated as $key => $value) {
            $this->settings->set($key, $value);
        }

        return back()->with('success', 'Affiliate settings updated.');
    }
}
