<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeCategoryController;
use App\Http\Controllers\PhotoStatusController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\TampletController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideogifController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // categories
    Route::resource('category', CategoryController::class);

    // sub-category
    Route::resource('sub-category', SubCategoryController::class);
    Route::get('/category/{cid}/subcategories', [SubCategoryController::class, 'getSubcategories'])->name('subcategories.Category');
    Route::post('/sub-category/status/update', [SubCategoryController::class, 'updateStatus'])->name('subcategory.updateStatus');

    // Role
     Route::resource('roles', RoleController::class);

    // admin-user
    Route::resource('admin-user', AdminController::class);

    // users
    Route::resource('user', UserController::class);
    Route::post('/user/status/update', [UserController::class, 'updateStatus'])->name('admin.updateStatus');

    // feedback list
    Route::get('/feedback', [UserController::class, 'feedbackList'])->name('feedback.list');
    Route::delete('/feedback/{id}', [UserController::class, 'deleteFeedback'])->name('feedback.delete');

    // home-category
    Route::resource('home-category', HomeCategoryController::class);  
    Route::post('/home-category/status/update', [HomeCategoryController::class, 'updateStatus'])->name('homecategory.updateStatus');
    Route::post('/home-category/show/update', [HomeCategoryController::class, 'showHome'])->name('homecategory.showHome');

    //plan
    Route::resource('plan', SubscriptionPlanController::class);
    Route::post('/plan/status/update', [SubscriptionPlanController::class, 'updateStatus'])->name('plan.updateStatus');
    Route::delete('/plan/item/{id}', [SubscriptionPlanController::class, 'deleteItem'])->name('plan.item.delete');

    //position
    Route::resource('position', PositionController::class);

    //videogif
    Route::resource('videogif', VideogifController::class);
    Route::post('/videogif/status/update', [VideogifController::class, 'updateStatus'])->name('videogif.updateStatus');

    //tamplet
    Route::resource('tamplet', TampletController::class);

    //photo category
    Route::resource('photo-status', PhotoStatusController::class);

    //photo
    Route::resource('photo', PhotoController::class);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';