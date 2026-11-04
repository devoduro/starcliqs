<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// Public website (request limits are defined in AppServiceProvider)
Route::controller(SiteController::class)->middleware('throttle:site')->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/services/{service}', 'service')->name('services.show');
    Route::get('/products', 'products')->name('products.index');
    Route::get('/products/{product}', 'product')->name('products.show');
    Route::get('/projects', 'projects')->name('projects.index');
    Route::get('/projects/{project}', 'project')->name('projects.show');
    Route::get('/academy', 'academy')->name('academy');
    Route::get('/trainings', fn () => redirect()->to(route('academy') . '#programs', 301))->name('trainings.index');
    Route::get('/trainings/{training}', 'training')->name('trainings.show');
    Route::get('/blog', 'blog')->name('blog.index');
    Route::get('/blog/{post}', 'post')->name('blog.show');
    Route::post('/enquiries', 'enquire')->middleware('throttle:forms')->name('enquiries.store');
    Route::post('/subscribe', 'subscribe')->middleware('throttle:forms')->name('subscribe');
});

// Admin sign-in
Route::middleware(['guest', 'throttle:login'])->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/admin/login', [LoginController::class, 'login']);
});
Route::post('/admin/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Admin panel
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('services', Admin\ServiceController::class)->except('show');
    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::resource('projects', Admin\ProjectController::class)->except('show');
    Route::resource('testimonials', Admin\TestimonialController::class)->except('show');
    Route::resource('trainings', Admin\TrainingController::class)->except('show');
    Route::resource('posts', Admin\PostController::class)->except('show');
    Route::resource('faqs', Admin\FaqController::class)->except('show');
    Route::resource('clients', Admin\ClientController::class)->except('show');

    Route::get('enquiries', [Admin\EnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('enquiries/{enquiry}', [Admin\EnquiryController::class, 'show'])->name('enquiries.show');
    Route::patch('enquiries/{enquiry}/unread', [Admin\EnquiryController::class, 'markUnread'])->name('enquiries.unread');
    Route::delete('enquiries/{enquiry}', [Admin\EnquiryController::class, 'destroy'])->name('enquiries.destroy');

    Route::get('visitors', [Admin\VisitorController::class, 'index'])->name('visitors.index');

    Route::get('subscribers', [Admin\SubscriberController::class, 'index'])->name('subscribers.index');
    Route::get('subscribers/export', [Admin\SubscriberController::class, 'export'])->name('subscribers.export');
    Route::delete('subscribers/{subscriber}', [Admin\SubscriberController::class, 'destroy'])->name('subscribers.destroy');

    Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');

    Route::get('profile', [Admin\ProfileController::class, 'edit'])->name('profile');
    Route::put('profile', [Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [Admin\ProfileController::class, 'password'])->name('profile.password');
    Route::post('admins', [Admin\ProfileController::class, 'storeAdmin'])->name('admins.store');
    Route::delete('admins/{user}', [Admin\ProfileController::class, 'destroyAdmin'])->name('admins.destroy');
});
