<?php


use App\Http\Controllers\FormController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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

// ADMIN LOGIN 
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::view('/brands', 'admin.base.view-brand')->name('brands.index');
    Route::view('/add-brands', 'admin.base.add-brand')->name('brands.create');
    Route::view('/sample', 'admin.base.sample')->name('sample');

    
    Route::view('/forms', 'admin.forms.create')->name('forms.create');
    Route::resource('forms', FormController::class);
    // Route::resource('posts', PostController::class);
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
