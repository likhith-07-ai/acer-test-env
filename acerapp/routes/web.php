<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\ResearchArticleController as AdminResearchArticleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DocCategoryController;
use App\Http\Controllers\Admin\ResearchCategoryController;
use App\Http\Controllers\Admin\ResearchTagController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\RatingsController;
use App\Http\Controllers\Public\ResearchArticleController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\DocumentController as PublicDocumentController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Public\PolicyController as PublicPolicyController;
use App\Http\Controllers\Public\RegulatorController;
use App\Http\Controllers\Public\PdfViewerController;
use App\Http\Controllers\Public\SitemapController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('public.home');

// Public routes (without /public prefix in URL)
Route::name('public.')->group(function () {
    // Redirect old about-us URL to about
    Route::get('/about-us', function () {
        return redirect()->route('public.about', [], 301);
    });

    Route::get('/about', [AboutController::class, 'index'])->name('about');

    // Ratings routes
    Route::get('/ratings', [RatingsController::class, 'index'])->name('ratings.index');
    Route::get('/ratings/criteria', [RatingsController::class, 'criteria'])->name('ratings.criteria');
    Route::get('/ratings/process', [RatingsController::class, 'process'])->name('ratings.process');

    // Research & Insights (Research Articles)
    Route::get('/research-insights', [ResearchArticleController::class, 'index'])->name('research.index');
    Route::get('/research-insights/{slug}', [ResearchArticleController::class, 'show'])->name('research.show');

    // Regulator routes
    Route::prefix('regulator')->group(function () {
        Route::get('/sebi', [RegulatorController::class, 'sebi'])->name('regulator.sebi');
        Route::get('/rbi', [RegulatorController::class, 'rbi'])->name('regulator.rbi');
        Route::get('/other-fsr', [RegulatorController::class, 'otherFsr'])->name('regulator.other-fsr');
    });

    // PDF Viewer
    Route::get('/pdf-viewer/{type}/{id}', [PdfViewerController::class, 'viewer'])->name('pdf.viewer');
    Route::get('/pdf-serve/{type}/{id}', [PdfViewerController::class, 'serve'])->name('pdf.serve');
    Route::get('/pdf-download/{type}/{id}', [PdfViewerController::class, 'download'])->name('pdf.download');

    // Contact
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    // Media & Press
    Route::get('/media-press', [App\Http\Controllers\Public\MediaPressController::class, 'index'])->name('media-press');
    Route::get('/api/press-releases/search', [App\Http\Controllers\Public\MediaPressController::class, 'apiSearch'])->name('api.press-releases.search');
    Route::get('/press-releases/{id}', [App\Http\Controllers\Public\PressReleaseController::class, 'show'])->name('press-releases.show');

    // Sitemap
    Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Documents
    Route::resource('documents', AdminDocumentController::class);
    Route::get('/documents/{document}/download', [AdminDocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/export/excel', [AdminDocumentController::class, 'export'])->name('documents.export');
    Route::get('/documents/export/zip', [AdminDocumentController::class, 'exportZip'])->name('documents.export.zip');
    Route::post('/documents/{document}/toggle-access', [AdminDocumentController::class, 'toggleAccess'])->name('documents.toggle-access');

    // Document Categories Management (inline)
    Route::post('/documents/categories/store', [AdminDocumentController::class, 'storeCategory'])->name('documents.categories.store');
    Route::put('/documents/categories/{category}', [AdminDocumentController::class, 'updateCategory'])->name('documents.categories.update');
    Route::delete('/documents/categories/{category}', [AdminDocumentController::class, 'deleteCategory'])->name('documents.categories.delete');
    Route::get('/documents/categories/list', [AdminDocumentController::class, 'getCategories'])->name('documents.categories.list');

    // Document Categories CRUD (Full Management)
    Route::resource('doc-categories', DocCategoryController::class);

    // Users Management (Only Super Admin)
    Route::middleware(['super_admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Roles Management (Only Super Admin)
    Route::middleware(['super_admin'])->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Research Articles - Full access for admin/super_admin
    Route::resource('research-articles', AdminResearchArticleController::class);
    Route::get('/research-articles/{researchArticle}/preview', [AdminResearchArticleController::class, 'preview'])->name('research-articles.preview');
    Route::get('/research-articles/approval/queue', [AdminResearchArticleController::class, 'approvalQueue'])->name('research-articles.approval-queue');
    Route::post('/research-articles/{researchArticle}/approve', [AdminResearchArticleController::class, 'approve'])->name('research-articles.approve');
    Route::post('/research-articles/{researchArticle}/reject', [AdminResearchArticleController::class, 'reject'])->name('research-articles.reject');
    Route::post('/research-articles/{researchArticle}/publish', [AdminResearchArticleController::class, 'publish'])->name('research-articles.publish');

    // Research Articles Categories & Tags Management (inline)
    Route::post('/research-articles/categories/store', [AdminResearchArticleController::class, 'storeCategory'])->name('research-articles.categories.store');
    Route::put('/research-articles/categories/{category}', [AdminResearchArticleController::class, 'updateCategory'])->name('research-articles.categories.update');
    Route::delete('/research-articles/categories/{category}', [AdminResearchArticleController::class, 'deleteCategory'])->name('research-articles.categories.delete');
    Route::get('/research-articles/categories/list', [AdminResearchArticleController::class, 'getCategories'])->name('research-articles.categories.list');

    Route::post('/research-articles/tags/store', [AdminResearchArticleController::class, 'storeTag'])->name('research-articles.tags.store');
    Route::put('/research-articles/tags/{tag}', [AdminResearchArticleController::class, 'updateTag'])->name('research-articles.tags.update');
    Route::delete('/research-articles/tags/{tag}', [AdminResearchArticleController::class, 'deleteTag'])->name('research-articles.tags.delete');
    Route::get('/research-articles/tags/list', [AdminResearchArticleController::class, 'getTags'])->name('research-articles.tags.list');
    Route::post('/research-articles/upload-image', [AdminResearchArticleController::class, 'uploadImage'])->name('research-articles.upload-image');
    Route::post('/research-articles/upload-document', [AdminResearchArticleController::class, 'uploadDocument'])->name('research-articles.upload-document');

    // Research Categories CRUD (Full Management)
    Route::resource('research-categories', ResearchCategoryController::class);

    // Research Tags CRUD (Full Management)
    Route::resource('research-tags', ResearchTagController::class);

    // Policies
    Route::resource('policies', PolicyController::class);
    Route::get('/policies/{policy}/download', [PolicyController::class, 'download'])->name('policies.download');
    Route::get('/policies/export/excel', [PolicyController::class, 'export'])->name('policies.export');
    Route::get('/policies/export/zip', [PolicyController::class, 'exportZip'])->name('policies.export.zip');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');

    // Press Releases
    Route::resource('press-releases', App\Http\Controllers\Admin\PressReleaseController::class);

    // User profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Research Articles routes for Authors and Reviewers (who are not admin/super_admin)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Authors can manage their own articles (if not admin/super_admin)
    Route::middleware(['author'])->group(function () {
        Route::resource('research-articles', AdminResearchArticleController::class)->except(['approve', 'reject', 'publish', 'approvalQueue']);
    });

    // Reviewers can approve/reject (if not admin/super_admin)
    Route::middleware(['reviewer'])->group(function () {
        Route::get('/research-articles/approval/queue', [AdminResearchArticleController::class, 'approvalQueue'])->name('research-articles.approval-queue');
        Route::post('/research-articles/{researchArticle}/approve', [AdminResearchArticleController::class, 'approve'])->name('research-articles.approve');
        Route::post('/research-articles/{researchArticle}/reject', [AdminResearchArticleController::class, 'reject'])->name('research-articles.reject');
    });
});

require __DIR__ . '/auth.php';
