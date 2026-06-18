<?php


use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::prefix('admin')->group(function () {
    Route::view('/brands', 'admin.base.view-brand')->name('brands.index');
    Route::view('/add-brands', 'admin.base.add-brand')->name('brands.create');
    Route::view('/sample', 'admin.base.sample')->name('sample');
    Route::view('/dashboard', 'admin.base.dashboard')->name('dashboard');

    
    Route::view('/forms', 'admin.forms.create')->name('forms.create');
    Route::resource('forms', FormController::class);
       Route::get('forms/{id}/submissions', [FormController::class, 'submissions'])
        ->name('forms.submissions');
    Route::get('forms/{id}/submissions/export', [FormController::class, 'exportSubmissions'])
        ->name('forms.submissions.export');
    // Route::resource('posts', PostController::class);
});



## cache clear
Route::get('/clear', function() {
   
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    // Artisan::call('optimize');
 
    return "All caches cleared successfully!..!";
 
 });
