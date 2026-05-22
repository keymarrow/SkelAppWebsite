<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\ContactSubmissionController as AdminContactSubmissionController;
use App\Http\Controllers\Admin\NewsPostController as AdminNewsPostController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PagePreviewController as AdminPagePreviewController;
use App\Http\Controllers\Admin\NewsPostPreviewController as AdminNewsPostPreviewController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

$registerPublicRoutes = function (): void {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::view('/terms-of-service', 'terms')->name('terms.show');
    Route::view('/faq', 'faq')->name('faq.show');
    Route::view('/pricing', 'pricing')->name('pricing.show');
    Route::view('/features', 'features')->name('features.show');

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

        // Persist the submission first so it's never lost even if email fails.
        if (\App\Models\ContactSubmission::isAvailable()) {
            \App\Models\ContactSubmission::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'company' => $data['company'],
                'ip_address' => $request->ip(),
                'user_agent' => \Illuminate\Support\Str::limit((string) $request->userAgent(), 500, ''),
            ]);
        }

        $recipient = content('contact.form.recipient_email', 'pos@skelapp.tz');
        $subjectPrefix = content('contact.form.subject_prefix', 'Demo Request');
        $successTemplate = content('contact.form.success_message', "Thank you, {first_name}! We've received your request and will be in touch shortly.");

        $body = implode("\n", [
            'New demo request from the SkelApp website.',
            '',
            "Name:     {$data['first_name']} {$data['last_name']}",
            "Email:    {$data['email']}",
            "Phone:    {$data['phone']}",
            "Company:  {$data['company']}",
        ]);

        try {
            Mail::raw($body, function ($message) use ($data, $recipient, $subjectPrefix) {
                $message
                    ->to($recipient)
                    ->replyTo($data['email'], "{$data['first_name']} {$data['last_name']}")
                    ->subject("{$subjectPrefix} – {$data['first_name']} {$data['last_name']} ({$data['company']})");
            });
        } catch (\Throwable $e) {
            // Email failure shouldn't break the form — submission is already saved.
            report($e);
        }

        $successMessage = strtr($successTemplate, [
            '{first_name}' => $data['first_name'],
            '{last_name}' => $data['last_name'],
            '{company}' => $data['company'],
        ]);

        return redirect()->route('contact.show')
            ->with('success', $successMessage);
    })->name('contact.send');

    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
};

$registerAdminRoutes = function (): void {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthenticatedSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('/media/images', [AdminNewsPostController::class, 'mediaLibrary'])->name('media.images.index');
        Route::post('/posts/content-images', [AdminNewsPostController::class, 'uploadContentImage'])->name('posts.content-images.store');
        Route::get('/posts/create/preview', [AdminNewsPostPreviewController::class, 'create'])->name('posts.create.preview');
        Route::post('/posts/create/preview-sync', [AdminNewsPostPreviewController::class, 'syncCreate'])->name('posts.create.preview.sync');
        Route::get('/posts/{post}/preview', [AdminNewsPostPreviewController::class, 'show'])->name('posts.preview');
        Route::post('/posts/{post}/preview-sync', [AdminNewsPostPreviewController::class, 'sync'])->name('posts.preview.sync');
        Route::resource('/posts', AdminNewsPostController::class)->except(['show']);

        Route::get('/submissions', [AdminContactSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [AdminContactSubmissionController::class, 'show'])->name('submissions.show');
        Route::delete('/submissions/{submission}', [AdminContactSubmissionController::class, 'destroy'])->name('submissions.destroy');

        Route::post('/pages/images/upload', [AdminPageController::class, 'uploadImage'])->name('pages.images.upload');
        Route::get('/pages/{slug}/preview', [AdminPagePreviewController::class, 'show'])->name('pages.preview');
        Route::post('/pages/{slug}/preview-sync', [AdminPagePreviewController::class, 'sync'])->name('pages.preview.sync');
        Route::get('/pages/{slug}', [AdminPageController::class, 'edit'])->name('pages.edit');
        Route::post('/pages/{slug}', [AdminPageController::class, 'update'])->name('pages.update');
        Route::post('/pages/{slug}/publish', [AdminPageController::class, 'publish'])->name('pages.publish');
        Route::post('/pages/{slug}/revert', [AdminPageController::class, 'revert'])->name('pages.revert');
    });
};

$adminGroup = Route::as('admin.');
$publicGroup = Route::as('');
$configuredPublicHost = config('cms.public_host');
$configuredAdminHost = config('cms.admin_host');

if (filled($configuredPublicHost)) {
    $publicGroup->domain($configuredPublicHost)->group($registerPublicRoutes);
} else {
    $publicGroup->group($registerPublicRoutes);
}

if (filled($configuredAdminHost)) {
    $adminGroup->domain($configuredAdminHost)->group($registerAdminRoutes);
} else {
    $adminPrefix = trim((string) config('cms.admin_prefix', 'admin'), '/');
    $adminGroup->prefix($adminPrefix)->group($registerAdminRoutes);
}
