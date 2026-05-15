<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\NewsPostController as AdminNewsPostController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/terms-of-service', 'terms')->name('terms.show');
Route::view('/faq', 'faq')->name('faq.show');

Route::get('/contact', function () {
    return view('contact');
})->name('contact.show');

Route::post('/contact', function (Request $request) {
    $data = $request->validate([
        'first_name' => ['required', 'string', 'max:100'],
        'last_name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:255'],
        'phone' => ['required', 'string', 'max:30'],
        'company' => ['required', 'string', 'max:200'],
    ]);

    $body = implode("\n", [
        'New demo request from the SkelApp website.',
        '',
        "Name:     {$data['first_name']} {$data['last_name']}",
        "Email:    {$data['email']}",
        "Phone:    {$data['phone']}",
        "Company:  {$data['company']}",
    ]);

    Mail::raw($body, function ($message) use ($data) {
        $message
            ->to('pos@skelapp.tz')
            ->replyTo($data['email'], "{$data['first_name']} {$data['last_name']}")
            ->subject("Demo Request – {$data['first_name']} {$data['last_name']} ({$data['company']})");
    });

    return redirect()->route('contact.show')
        ->with('success', "Thank you, {$data['first_name']}! We've received your request and will be in touch shortly.");
})->name('contact.send');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

$registerAdminRoutes = function (): void {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthenticatedSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::resource('/posts', AdminNewsPostController::class)->except(['show']);
    });
};

$adminGroup = Route::as('admin.');
$configuredAdminHost = config('cms.admin_host');

if (filled($configuredAdminHost)) {
    $adminGroup->domain($configuredAdminHost)->group($registerAdminRoutes);
} else {
    $adminPrefix = trim((string) config('cms.admin_prefix', 'admin'), '/');
    $adminGroup->prefix($adminPrefix)->group($registerAdminRoutes);
}
