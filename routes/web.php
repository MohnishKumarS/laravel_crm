<?php


use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FormController;
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

    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::view('/brands', 'admin.base.view-brand')->name('brands.index');
    Route::view('/add-brands', 'admin.base.add-brand')->name('brands.create');
    Route::view('/sample', 'admin.base.sample')->name('sample');

    // DYNAMIC FORM BUILDER
    Route::view('/forms', 'admin.forms.create')->name('forms.create');
    Route::resource('forms', FormController::class);

    Route::get('forms/{id}/submissions', [FormController::class, 'submissions'])->name('forms.submissions');
    Route::get('forms/{id}/submissions/export', [FormController::class, 'exportSubmissions'])->name('forms.submissions.export');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::view('/posts', 'admin.posts.create')->name('posts.create');
    
    Route::resource('posts', PostController::class); 
    
    

    // SETTINGS
    Route::controller(SettingController::class)->group(function(){
        Route::get('/settings','index')->name('settings');
        Route::post('/settings','update')->name('settings.update');
    });
});

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
