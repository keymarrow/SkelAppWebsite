<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AffiliateApplicationController as AdminAffiliateApplicationController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\ContactSubmissionController as AdminContactSubmissionController;
use App\Http\Controllers\Admin\NewsPostController as AdminNewsPostController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PagePreviewController as AdminPagePreviewController;
use App\Http\Controllers\Admin\NewsPostPreviewController as AdminNewsPostPreviewController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SitemapController;
use App\Services\BeemSmsService;
use App\Support\CmsCatalogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

$registerPublicRoutes = function (): void {
    $integrationItems = static function (): array {
        return collect(content_list('integrations.items', CmsCatalogs::integrationItems()))
            ->filter(fn ($item) => is_array($item) && filled($item['slug'] ?? null))
            ->mapWithKeys(fn ($item) => [(string) $item['slug'] => $item])
            ->all();
    };

    $retailerPages = static function (): array {
        return collect(content_list('retailers.pages', CmsCatalogs::retailerPages()))
            ->filter(fn ($item) => is_array($item) && filled($item['slug'] ?? null))
            ->mapWithKeys(fn ($item) => [(string) $item['slug'] => $item])
            ->all();
    };

    $hardwareProducts = static function (): array {
        return collect(content_list('hardware.products', CmsCatalogs::hardwareProducts()))
            ->filter(fn ($item) => is_array($item) && filled($item['slug'] ?? null))
            ->mapWithKeys(fn ($item) => [(string) $item['slug'] => $item])
            ->all();
    };

    $issueAffiliateCaptcha = static function (): string {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captcha = '';

        for ($i = 0; $i < 6; $i++) {
            $captcha .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        session(['affiliate_application_captcha' => $captcha]);

        return $captcha;
    };

    $buildAffiliateCaptchaGlyphs = static function (string $captcha): array {
        $glyphs = [];

        foreach (str_split($captcha) as $index => $char) {
            $glyphs[] = [
                'char' => $char,
                'rotate' => [-16, -7, 5, -11, 9, 13][$index % 6] + random_int(-2, 2),
                'shift' => [-2, 3, -1, 4, -3, 2][$index % 6] + random_int(-1, 1),
            ];
        }

        return $glyphs;
    };

    Route::get('/', function () {
        return view('welcome');
    });

    Route::view('/terms-of-service', 'terms')->name('terms.show');
    Route::view('/privacy-policy', 'privacy')->name('privacy.show');
    Route::view('/affiliate-program', 'affiliate')->name('affiliate.show');
    Route::post('/download/sms', function (Request $request, BeemSmsService $beemSms) {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'country_code' => ['nullable', 'string', 'max:10', 'regex:/^\+?[0-9]{1,4}$/'],
        ]);

        $countryCode = $data['country_code'] ?? content('global.download.country_code', '+255');
        $destination = $beemSms->normalizeDestination($data['phone'], $countryCode);

        if ($destination === '') {
            return response()->json([
                'message' => 'Please enter a valid mobile number.',
            ], 422);
        }

        $appleUrl = trim((string) content('global.app_badges.apple_url', config('services.beem.apple_url', '')));
        $googleUrl = trim((string) content('global.app_badges.google_url', config('services.beem.google_url', '')));

        if ($appleUrl === '' || $appleUrl === '#' || $googleUrl === '' || $googleUrl === '#') {
            return response()->json([
                'message' => 'The app download links are not configured yet.',
            ], 503);
        }

        try {
            $beemSms->send(
                $destination,
                implode("\n", [
                    'Download SkelApp:',
                    'iPhone: '.$appleUrl,
                    'Android: '.$googleUrl,
                ]),
                (string) random_int(100000, 999999)
            );
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'We could not send the download links right now. Please try again shortly.',
            ], 502);
        }

        return response()->json([
            'message' => "We've sent the App Store and Google Play links to your phone.",
        ]);
    })->middleware('throttle:5,1')->name('download.sms');

    Route::get('/affiliate-program/apply', function () use ($issueAffiliateCaptcha, $buildAffiliateCaptchaGlyphs) {
        $captcha = $issueAffiliateCaptcha();

        return view('affiliate-apply', [
            'captchaGlyphs' => $buildAffiliateCaptchaGlyphs($captcha),
        ]);
    })->name('affiliate.apply.show');

    Route::post('/affiliate-program/apply', function (Request $request) {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone_country_code' => ['required', 'string', 'max:10', 'regex:/^\+[0-9]{1,4}$/'],
            'phone_number' => ['required', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:120'],
            'primary_promotional_method' => ['required', 'string', 'max:120'],
            'hear_about_program' => ['required', 'string', 'max:120'],
            'marketing_details' => ['nullable', 'string', 'max:2500'],
            'captcha' => ['required', 'string', 'size:6'],
            'accepts_agreement' => ['accepted'],
            'eligibility_confirmed' => ['accepted'],
        ]);

        $expectedCaptcha = strtoupper((string) $request->session()->get('affiliate_application_captcha', ''));
        $providedCaptcha = strtoupper(trim((string) $data['captcha']));

        if ($expectedCaptcha === '' || ! hash_equals($expectedCaptcha, $providedCaptcha)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'captcha' => 'The captcha code did not match. Please try again.',
            ]);
        }

        $phoneDisplay = trim($data['phone_country_code'].' '.$data['phone_number']);
        $summary = \Illuminate\Support\Str::limit(
            'Affiliate application - '.$data['country'].' - '.$data['primary_promotional_method'],
            200,
            ''
        );

        if (\App\Models\AffiliateApplication::isAvailable()) {
            \App\Models\AffiliateApplication::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone_country_code' => $data['phone_country_code'],
                'phone_number' => $data['phone_number'],
                'country' => $data['country'],
                'primary_promotional_method' => $data['primary_promotional_method'],
                'hear_about_program' => $data['hear_about_program'],
                'marketing_details' => $data['marketing_details'] ?? null,
                'accepts_agreement' => true,
                'accepts_marketing' => $request->boolean('accepts_marketing'),
                'eligibility_confirmed' => true,
                'ip_address' => $request->ip(),
                'user_agent' => \Illuminate\Support\Str::limit((string) $request->userAgent(), 500, ''),
            ]);
        }

        if (\App\Models\ContactSubmission::isAvailable()) {
            \App\Models\ContactSubmission::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $phoneDisplay,
                'company' => $summary,
                'ip_address' => $request->ip(),
                'user_agent' => \Illuminate\Support\Str::limit((string) $request->userAgent(), 500, ''),
            ]);
        }

        $recipient = content('contact.form.recipient_email', 'pos@skelapp.tz');
        $subjectPrefix = config('affiliate_program.application.subject_prefix', 'Affiliate Application');
        $successTemplate = config('affiliate_program.application.success_message', "Thanks, {first_name}. We've received your affiliate application and will review it within five business days.");

        $body = implode("\n", array_filter([
            'New affiliate application from the SkelApp website.',
            '',
            "Name:                  {$data['first_name']} {$data['last_name']}",
            "Email:                 {$data['email']}",
            "Phone:                 {$phoneDisplay}",
            "Country:               {$data['country']}",
            "Primary method:        {$data['primary_promotional_method']}",
            "Heard about program:   {$data['hear_about_program']}",
            'Accepts agreement:     Yes',
            'Accepts marketing:     '.($request->boolean('accepts_marketing') ? 'Yes' : 'No'),
            'Eligibility confirmed: Yes',
            !empty($data['marketing_details']) ? '' : null,
            !empty($data['marketing_details']) ? 'Marketing details:' : null,
            !empty($data['marketing_details']) ? trim((string) $data['marketing_details']) : null,
        ]));

        try {
            Mail::raw($body, function ($message) use ($data, $recipient, $subjectPrefix) {
                $message
                    ->to($recipient)
                    ->replyTo($data['email'], "{$data['first_name']} {$data['last_name']}")
                    ->subject("{$subjectPrefix} - {$data['first_name']} {$data['last_name']}");
            });
        } catch (\Throwable $e) {
            report($e);
        }

        $request->session()->forget('affiliate_application_captcha');

        $successMessage = strtr($successTemplate, [
            '{first_name}' => $data['first_name'],
            '{last_name}' => $data['last_name'],
        ]);

        return redirect()->route('affiliate.apply.show')
            ->with('success', $successMessage);
    })->name('affiliate.apply.submit');

    Route::view('/faq', 'faq')->name('faq.show');
    Route::view('/pricing', 'pricing')->name('pricing.show');
    Route::view('/features', 'features')->name('features.show');
    Route::view('/why-skelapp', 'why')->name('why.show');
    Route::get('/integrations', function () use ($integrationItems) {
        return view('integrations', [
            'hero' => config('integrations.hero', []),
            'categories' => config('integrations.categories', []),
            'items' => $integrationItems(),
            'faq' => config('integrations.faq', []),
        ]);
    })->name('integrations.index');

    Route::get('/integrations/{integration}', function (string $integration) use ($integrationItems) {
        $items = $integrationItems();
        abort_unless(isset($items[$integration]), 404);

        return view('integration', [
            'slug' => $integration,
            'integration' => $items[$integration],
        ]);
    })->name('integrations.show');

    Route::post('/integrations/{integration}/interest', function (Request $request, string $integration) use ($integrationItems) {
        $items = $integrationItems();
        abort_unless(isset($items[$integration]), 404);

        $item = $items[$integration];

        $data = $request->validateWithBag('integrationInterest', [
            'full_name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        [$firstName, $lastName] = array_pad(preg_split('/\s+/', trim($data['full_name']), 2), 2, null);
        $firstName = \Illuminate\Support\Str::limit((string) ($firstName ?: $data['full_name']), 100, '');
        $lastName = \Illuminate\Support\Str::limit(trim((string) ($lastName ?: 'Interest')), 100, '');
        $company = \Illuminate\Support\Str::limit(($item['name'] ?? 'SkelApp').' integration waitlist', 200, '');

        if (\App\Models\ContactSubmission::isAvailable()) {
            \App\Models\ContactSubmission::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $data['email'],
                'phone' => 'Not provided',
                'company' => $company,
                'ip_address' => $request->ip(),
                'user_agent' => \Illuminate\Support\Str::limit((string) $request->userAgent(), 500, ''),
            ]);
        }

        $recipient = content('contact.form.recipient_email', 'pos@skelapp.tz');
        $body = implode("\n", [
            'New integration waitlist request from the SkelApp website.',
            '',
            'Integration: '.($item['name'] ?? ucfirst(str_replace('-', ' ', $integration))),
            'Name:        '.$data['full_name'],
            'Email:       '.$data['email'],
        ]);

        try {
            Mail::raw($body, function ($message) use ($data, $recipient, $item) {
                $message
                    ->to($recipient)
                    ->replyTo($data['email'], $data['full_name'])
                    ->subject('Integration Waitlist - '.($item['name'] ?? 'SkelApp'));
            });
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->to(route('integrations.show', $integration).'#integration-interest')
            ->with('integration_interest_success', "Thanks, {$data['full_name']}. We'll let you know when ".($item['name'] ?? 'this integration')." is available.");
    })->name('integrations.interest');

    Route::get('/point-of-sale', function () {
        return view('point-of-sale', [
            'shared' => config('hardware_products.shared', []),
        ]);
    })->name('pos.show');

    Route::view('/retailers', 'retailers-index')->name('retailers.index');

    Route::get('/retailers/{retailer}', function (string $retailer) use ($retailerPages) {
        $retailers = $retailerPages();
        abort_unless(isset($retailers[$retailer]), 404);

        return view('retailer', [
            'slug'   => $retailer,
            'r'      => $retailers[$retailer],
        ]);
    })->name('retailers.show');
    Route::view('/hardware', 'hardware')->name('hardware.show');

    Route::get('/hardware/{product}', function (string $product) use ($hardwareProducts) {
        $products = $hardwareProducts();
        abort_unless(isset($products[$product]), 404);

        return view('hardware-product', [
            'slug'    => $product,
            'product' => $products[$product],
        ]);
    })->name('hardware.product');

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
        Route::get('/dashboard/data', [AdminDashboardController::class, 'data'])->name('dashboard.data');
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

        Route::get('/affiliate-applications', [AdminAffiliateApplicationController::class, 'index'])->name('affiliate-applications.index');
        Route::get('/affiliate-applications/{application}', [AdminAffiliateApplicationController::class, 'show'])->name('affiliate-applications.show');
        Route::delete('/affiliate-applications/{application}', [AdminAffiliateApplicationController::class, 'destroy'])->name('affiliate-applications.destroy');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

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
