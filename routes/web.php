<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomframeController;
use App\Http\Controllers\FontController;
use App\Http\Controllers\FrameController;
use App\Http\Controllers\HomeCategoryController;
use App\Http\Controllers\PhotoStatusController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SubFrameController;
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
    Route::get('/sub-category-export', [SubCategoryController::class, 'export'])->name('subcategory.export');
    Route::post('/sub-category-import', [SubCategoryController::class, 'import'])->name('subcategory.import');
    Route::get('/sub-category-template', [SubCategoryController::class, 'downloadTemplate'])->name('subcategory.template');

    // Role
     Route::resource('roles', RoleController::class);

    // admin-user
    Route::resource('admin-user', AdminController::class);

    // users
    Route::resource('user', UserController::class);
    Route::post('/user/status/update', [UserController::class, 'updateStatus'])->name('admin.updateStatus');
   
    // change-password
    Route::get('/user/{id}/change-password', [UserController::class, 'changePassword'])->name('user.changePassword');
    Route::post('/user/{id}/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
    
    
    // Custom Frame Routes
    Route::get('/user/{id}/customframe', [CustomframeController::class, 'index'])->name('user.customframe');
    Route::get('/user/{id}/customframe/create', [CustomframeController::class, 'create'])->name('create.customframe');
    Route::post('/user/{id}/customframe/store', [CustomframeController::class, 'store'])->name('store.customframe');
    Route::get('/user/{id}/customframe/edit/{cid}', [CustomframeController::class, 'edit'])->name('edit.customframe');
    Route::put('/user/{id}/customframe/update/{cid}', [CustomframeController::class, 'update'])->name('update.customframe');
    Route::delete('/user/{id}/customframe/delete/{cid}', [CustomframeController::class, 'destroy'])->name('delete.customframe');
    Route::post('/customframe/status/update', [CustomframeController::class, 'updateStatus'])->name('customframe.updateStatus');



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

    // frames
    Route::resource('frame', FrameController::class);
    Route::post('/frame/status/update', [FrameController::class, 'updateStatus'])->name('frame.updateStatus');
    Route::post('/frame/pay/update', [FrameController::class, 'updateFreePaid'])->name('frame.updateFreePaid');

     // sub-frames
    Route::resource('sub-frame', SubFrameController::class);
    Route::post('/subframe/status/update', [SubFrameController::class, 'updateStatus'])->name('sub-frame.updateStatus');

    // fonts
    Route::resource('fonts', FontController::class);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';