<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeCategoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SubscriptionPlanController;
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


    // home-category
    Route::resource('home-category', HomeCategoryController::class);  
    Route::post('/home-category/status/update', [HomeCategoryController::class, 'updateStatus'])->name('homecategory.updateStatus');
    Route::post('/home-category/show/update', [HomeCategoryController::class, 'showHome'])->name('homecategory.showHome');

    //plan
    Route::resource('plan', SubscriptionPlanController::class);
    Route::post('/plan/status/update', [SubscriptionPlanController::class, 'updateStatus'])->name('plan.updateStatus');
    Route::delete('/plan/item/{id}', [SubscriptionPlanController::class, 'deleteItem'])->name('plan.item.delete');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';