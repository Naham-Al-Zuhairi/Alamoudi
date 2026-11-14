<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicSiteController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\SetLocale;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ar')->name('home');
Route::get('/robots.txt', fn () => response("User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: ".url('/sitemap.xml')."\n", 200, ['Content-Type' => 'text/plain']));
Route::get('/sitemap.xml', function () {
    $paths = [];
    foreach (['ar', 'en'] as $locale) {
        foreach (['', '/projects', '/team', '/contact'] as $path) {
            $paths[] = url("/$locale$path");
        }
        foreach (Project::published()->pluck('slug') as $slug) {
            $paths[] = url("/$locale/projects/$slug");
        }
    }
    $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($paths as $path) {
        $xml .= '<url><loc>'.htmlspecialchars($path, ENT_XML1).'</loc></url>';
    }

    return response($xml.'</urlset>', 200, ['Content-Type' => 'application/xml']);
});
Route::middleware('auth')->get('/dashboard', fn () => auth()->user()->is_admin ? redirect('/admin') : inertia('Dashboard'))->name('dashboard');
Route::pattern('locale', 'ar|en');
Route::prefix('{locale}')->middleware(SetLocale::class)->group(function () {
    Route::get('/', [PublicSiteController::class, 'home'])->name('public.home');
    Route::get('/projects', [PublicSiteController::class, 'projects'])->name('public.projects');
    Route::get('/projects/{project:slug}', [PublicSiteController::class, 'project'])->name('public.project');
    Route::get('/team', [PublicSiteController::class, 'team'])->name('public.team');
    Route::get('/contact', [PublicSiteController::class, 'contact'])->name('public.contact');
    Route::post('/contact', [PublicSiteController::class, 'send'])->middleware('throttle:10,10')->name('public.contact.send');
});
Route::prefix('admin')->middleware(['auth', EnsureAdmin::class])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/content/{type}', [AdminController::class, 'index'])->name('admin.content');
    Route::post('/content/{type}', [AdminController::class, 'store'])->name('admin.content.store');
    Route::put('/content/{type}/{id}', [AdminController::class, 'update'])->name('admin.content.update');
    Route::delete('/content/{type}/{id}', [AdminController::class, 'destroy'])->name('admin.content.destroy');
    Route::get('/inbox', [AdminController::class, 'inbox'])->name('admin.inbox');
    Route::get('/inbox/{message}', [AdminController::class, 'message'])->name('admin.message');
    Route::patch('/inbox/{message}', [AdminController::class, 'mark'])->name('admin.message.mark');
    Route::post('/media', [AdminController::class, 'upload'])->name('admin.media.upload');
    Route::post('/projects/{project}/images', [AdminController::class, 'addImage'])->name('admin.project.images.store');
    Route::delete('/projects/{project}/images/{image}', [AdminController::class, 'removeImage'])->name('admin.project.images.destroy');
});

require __DIR__.'/settings.php';
