<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationAddController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AppSliderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComplainController;
use App\Http\Controllers\CustomframeController;
use App\Http\Controllers\FaqController;
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
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/today-festival-posts', [DashboardController::class, 'todayFestivalPosts'])->name('dashboard.today-festival-posts');
    Route::get('/dashboard/upcoming-festival-posts', [DashboardController::class, 'upcomingFestivalPosts'])->name('dashboard.upcoming-festival-posts');
    Route::get('/dashboard/upcoming-festivals', [DashboardController::class, 'upcomingFestivals'])->name('dashboard.upcoming-festivals');
    Route::get('/dashboard/category-template-count', [DashboardController::class, 'categoryWiseTemplateCount'])->name('dashboard.category-template-count');
    Route::get('/dashboard/category-photo-count', [DashboardController::class, 'categoryWisePhotoCount'])->name('dashboard.category-photo-count');
    Route::get('/dashboard/paid-user-count', [DashboardController::class, 'paidWiseUserCount'])->name('dashboard.paid-user-count');
    Route::get('/dashboard/custom-report', [DashboardController::class, 'customReport'])->name('dashboard.custom-report');
    Route::get('/dashboard/sms-log', [DashboardController::class, 'smsLog'])->name('dashboard.sms-log');

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

    // users transactions 
    Route::get('/users-transactions', [UserController::class, 'transactionList'])->name('users.transactions.list');

    // user post list    
    Route::get('/post-list', [UserController::class, 'postList'])->name('post.list');
    Route::delete('/post/{id}', [UserController::class, 'deletePost'])->name('user.post.delete');
   
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

    // app-slider
    Route::resource('app-slider',AppSliderController::class);
    Route::post('/app-slider/status/update', [AppSliderController::class, 'updateStatus'])->name('app-slider.updateStatus');

    // faqs
    Route::resource('faqs',FaqController::class);
    Route::post('/faq/status/update', [FaqController::class, 'updateStatus'])->name('faq.updateStatus');
    
    // complain 
    Route::get('/complain', [ComplainController::class, 'index'])->name('complain.list');
    Route::get('/complain/reply/{id}', [ComplainController::class, 'reply'])->name('compain.reply');
    Route::post('/complain/reply/{id}', [ComplainController::class, 'replyStore'])->name('compain.reply.store');

    // payment
    Route::get('/payment-failed', [PaymentController::class, 'failedList'])->name('payment.failed');
    Route::get('/paid-subscription', [PaymentController::class, 'paidsubscriptionList'])->name('payment.paid-subscription');
    Route::get('/paid/other-number-payment', [PaymentController::class, 'Othernumberpayment'])->name('payment.othernumberpayment');
    Route::delete('/other-number-payment/{id}', [PaymentController::class, 'deleteOthernumberpayment'])->name('payment.othernumberpayment.destroy');
    Route::get('/payment-active', [PaymentController::class, 'paymentActive'])->name('payment.active');
    Route::get('/payment-deactive', [PaymentController::class, 'paymentDeactive'])->name('payment.deactive');
    Route::post('/payment/get-user-data', [PaymentController::class, 'getUserData'])->name('payment.getUserData');
    Route::post('/payment/manually', [PaymentController::class, 'paymentManually'])->name('payment.manually');
    Route::get('/trial-subscription', [PaymentController::class, 'trialsubscriptionList'])->name('payment.trial-subscription');


    

    
    

    // application
    Route::resource('application',ApplicationAddController::class);

    // advertisement
    Route::resource('advertisement',AdvertisementController::class);

    // settings
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [DashboardController::class, 'updateSettings'])->name('settings.update');

    // image zip
    Route::get('/image-zip-download', [DashboardController::class, 'imagezipDownload'])->name('image-zip.download');
    
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';