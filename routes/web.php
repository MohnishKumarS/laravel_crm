<?php



use App\Http\Controllers\AffiliateCommissionController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\AffiliatePayoutController;
use App\Http\Controllers\AffiliateSettingController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\HomeHeroController;
use App\Http\Controllers\Marketplace\AnalyticsController as shopAnalytics;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return view('admin.dashboard');
// });

Route::get('/', function () {

    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/test-mail', function () {

    Mail::raw('This is a test email from Laravel.', function ($message) {
        $message->to('mohnish101998@gmail.com')
            ->subject('Laravel Test Mail');
    });

    return 'Mail sent';
});

// ADMIN LOGIN 
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::view('/register', 'auth.register')->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

// FORGOT PASSWORD
Route::controller(ForgotPasswordController::class)->group(function () {

    Route::get('/forgot-password', 'index')->name('forgot.password');
    Route::post('/forgot-password', 'sendOtp')->name('forgot.password.send');

    Route::get('/verify-otp', 'verifyPage')->name('verify.otp');
    Route::post('/verify-otp', 'verifyOtp')->name('verify.otp.submit');

    Route::get('/reset-password', 'showResetPasswordForm')->name('reset.password');
    Route::post('/reset-password', 'resetPassword')->name('reset.password.submit');
});

// ADMIN ROUTES
Route::middleware(['auth', 'admin'])->group(function () {

    // Route::get('dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/visitors-per-day', [DashboardController::class, 'visitorsPerDay'])->name('dashboard.visitors-per-day');

    Route::view('/brands', 'admin.base.view-brand')->name('brands.index');
    Route::view('/add-brands', 'admin.base.add-brand')->name('brands.create');
    Route::view('/sample', 'admin.base.sample')->name('sample');

    // DYNAMIC FORM BUILDER
    Route::resource('forms', FormController::class);

    Route::get('forms/{id}/submissions', [FormController::class, 'submissions'])->name('forms.submissions');
    Route::get('forms/{id}/submissions/export', [FormController::class, 'exportSubmissions'])->name('forms.submissions.export');

    Route::delete('submissions/{id}', [FormController::class, 'deleteSubmission'])->name('forms.submissions.delete');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // POSTS - BLOGS
    Route::resource('posts', PostController::class);

    // NOTIFICATION
    Route::get('notifications/mark-all-read', [DashboardController::class, 'markAllRead'])->name('notifications.markAllRead');

    // SETTINGS
    Route::controller(SettingController::class)->group(function () {
        Route::get('/settings', 'index')->name('settings');
        Route::post('/settings', 'update')->name('settings.update');
    });

    // ANALYTICS
    Route::get('analytics/visitors', [AnalyticsController::class, 'visitors'])->name('analytics.visitors');
    Route::get('/analytics/visitors/export', [AnalyticsController::class, 'exportVisitors'])->name('analytics.visitors.export');

    Route::get('/upload-test', [AnalyticsController::class, 'index']);
    Route::post('/upload-test', [AnalyticsController::class, 'store'])->name('upload.test');

    Route::resource('campaigns', CampaignController::class)
        ->names('admin.campaigns')
        ->except(['show']);

    Route::resource('home-hero', HomeHeroController::class)
        ->parameters(['home-hero' => 'homeHero'])
        ->names('admin.home-hero')
        ->except(['show']);



    // ========================= MARKETPLACE SHOP
    Route::prefix('shop')->name('shop.')->group(function () {
        // DASHBOARD
        Route::get('/', [shopAnalytics::class, 'index'])->name('home');
        Route::get('visitors-per-day', [shopAnalytics::class, 'visitorsPerDay'])->name('visitors-per-day');
    });

    // ANALYTICS
    Route::get('analytics/shop', [shopAnalytics::class, 'shopVisitors'])->name('analytics.shop');
    Route::get('/analytics/shop/export', [shopAnalytics::class, 'exportShopVisitors'])->name('analytics.shop.export');
});
// affiliate admin routes
Route::controller(AffiliateSettingController::class)->group(function () {
    Route::get('affiliates/settings', 'edit')->name('affiliates.settings.edit');
    Route::put('affiliates/settings', 'update')->name('affiliates.settings.update');
});

Route::controller(AffiliateCommissionController::class)->group(function () {
    Route::get('affiliates/commissions', 'index')->name('affiliates.commissions');
    Route::put('affiliates/commissions/bulk-approve', 'bulkApprove')->name('affiliates.commissions.bulk-approve');
});

Route::controller(AffiliatePayoutController::class)->group(function () {
    Route::get('affiliates/payouts', 'index')->name('affiliates.payouts');
    Route::post('affiliates/payouts/create-batch', 'createBatch')->name('affiliates.payouts.create-batch');
    Route::put('affiliates/payouts/{payout}/mark-paid', 'markPaid')->name('affiliates.payouts.mark-paid');
});

Route::get('affiliates/create', [AffiliateController::class, 'create'])->name('affiliates.create');
Route::post('affiliates', [AffiliateController::class, 'store'])->name('affiliates.store');

Route::resource('affiliates', AffiliateController::class)->only(['index', 'show']);

Route::put('affiliates/{affiliate}/approve', [AffiliateController::class, 'approve'])->name('affiliates.approve');
Route::put('affiliates/{affiliate}/suspend', [AffiliateController::class, 'suspend'])->name('affiliates.suspend');
Route::put('affiliates/{affiliate}/reject', [AffiliateController::class, 'reject'])->name('affiliates.reject');
Route::put('affiliates/{affiliate}/rate', [AffiliateController::class, 'updateRate'])->name('affiliates.rate');

// MIGRATION
Route::get('/migrate', function () {

    $exitCode = Artisan::call('migrate', ['--force' => true]);
    Log::info('Migration process: ' . Artisan::output());

    return 'Migration completed successfully.';
});



Route::fallback(function () {
    return view('errors.404');
});

## cache clear
Route::get('/clear', function () {

    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    // Artisan::call('optimize');

    return "All caches cleared successfully!..!";
});
