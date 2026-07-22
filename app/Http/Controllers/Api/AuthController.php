<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\User;
use App\Services\AffiliateSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(private AffiliateSettingsService $settings) {
        
    }
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('name', $request->name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('api-token',['*'],now()->addHour())->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    // affiliate

    public function registerNew(Request $request)
{
    $request->validate([
        'name'         => ['required', 'string', 'max:255'],
        'email'        => ['required', 'email', 'unique:users,email'],
        'password'     => ['required', 'string', 'min:6'],
        'paypal_email' => ['nullable', 'email'],
    ]);

      $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'affiliate',
    ]);

    $affiliate = Affiliate::create([
        'user_id'         => $user->id,
        'affiliate_code'  => $this->generateUniqueCode($user->name),
        'slug'            => Str::slug($user->name . '-' . Str::random(4)),
        'commission_rate' => $this->settings->defaultCommissionRate(),
        'status'          => $this->settings->autoApproveAffiliates() ? 'approved' : 'pending',
        'approved_at'     => $this->settings->autoApproveAffiliates() ? now() : null,
        'paypal_email'    => $request->paypal_email,
    ]);

    // Issue a token immediately so the frontend doesn't need a separate
    // login call right after registering.
    $token = $user->createToken('api-token', ['*'], now()->addHour())->plainTextToken;

    return response()->json([
        'status'     => true,
        'token'      => $token,
        'user'       => $user,
        'affiliate'  => $this->transform($affiliate),
    ], 201);
}

    public function register(Request $request)
    {
        $user = $request->user(); // requires auth:sanctum

        if ($user->affiliate) {
            return response()->json(['message' => 'You already have an affiliate account.', 'affiliate' => $this->transform($user->affiliate)], 409);
        }

        $request->validate([
            'paypal_email' => ['nullable', 'email'],
        ]);

        $affiliate = Affiliate::create([
            'user_id'         => $user->id,
            'affiliate_code'  => $this->generateUniqueCode($user->name ?? 'AFF'),
            'slug'            => Str::slug(($user->name ?? 'affiliate') . '-' . Str::random(4)),
            'commission_rate' => $this->settings->defaultCommissionRate(),
            'status'          => $this->settings->autoApproveAffiliates() ? 'approved' : 'pending',
            'paypal_email'    => $request->paypal_email,
            'approved_at'     => $this->settings->autoApproveAffiliates() ? now() : null,
        ]);

        return response()->json(['affiliate' => $this->transform($affiliate)], 201);
    }

    /**
     * GET /api/affiliate/me
     */
    public function me(Request $request)
    {
        $affiliate = $request->user()->affiliate;

        if (!$affiliate) {
            return response()->json(['message' => 'Not an affiliate.'], 404);
        }

        return response()->json(['affiliate' => $this->transform($affiliate)]);
    }

    private function transform(Affiliate $affiliate): array
    {
        return [
            'id'                => $affiliate->id,
            'affiliate_code'    => $affiliate->affiliate_code,
            'slug'              => $affiliate->slug,
            'status'            => $affiliate->status,
            'commission_rate'   => (float) $affiliate->commission_rate,
            'referral_url'      => $affiliate->referralUrl(),
            'lifetime_earnings' => (float) $affiliate->lifetime_earnings,
            'lifetime_paid'     => (float) $affiliate->lifetime_paid,
        ];
    }

   private function generateUniqueCode(string $seed): string
{
    for ($i = 0; $i < 15; $i++) {
        $code = strtoupper(Str::slug($seed, '')) . rand(100, 999);
        $code = substr($code, 0, 10);
        if (!Affiliate::where('affiliate_code', $code)->exists()) {
            return $code;
        }
    }

    do {
        $code = strtoupper(Str::random(8));
    } while (Affiliate::where('affiliate_code', $code)->exists());

    return $code;
}
}
