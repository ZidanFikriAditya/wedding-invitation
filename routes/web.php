<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([
    'prefix' => 'admin',
    'middleware' => ['role:superadmin,admin'],
    'as' => 'admin.'
], function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');

    Route::group([
        'prefix' => 'select2',
        'as' => 'select2.'
    ], function () {
        Route::get('template-undangan', [\App\Http\Controllers\Admin\TemplateLetterController::class, 'select2'])->name('template-undangan');
    });

    Route::resource('template-undangan', \App\Http\Controllers\Admin\TemplateLetterController::class);
    Route::post('template-undangan-data/data-table', [\App\Http\Controllers\Admin\TemplateLetterController::class, 'data'])->name('template-undangan.data');
    Route::post('template-undangan-upload', [\App\Http\Controllers\Admin\TemplateLetterController::class, 'uploadTemplateLetter'])->name('template-undangan-upload');
    Route::resource('undangan', \App\Http\Controllers\Admin\ShareLetterController::class);
    Route::post('undangan/data-table', [\App\Http\Controllers\Admin\ShareLetterController::class, 'data'])->name('undangan.data');
    Route::post('import-undangan', [\App\Http\Controllers\Admin\ShareLetterController::class, 'importExcel'])->name('import-undangan');
    Route::get('undangan-share/{id}', [\App\Http\Controllers\Admin\ShareLetterController::class, 'sentStatus'])->name('undangan.sent-status');
    Route::resource('acara', \App\Http\Controllers\Admin\ProgramController::class)->only(['index', 'store']);

    Route::get('acara/{undangan}/undangan', [\App\Http\Controllers\Admin\ProgramController::class, 'templateLetterUtils'])->name('acara.template-undangan');

    Route::post('acara/save-image', [\App\Http\Controllers\Admin\ProgramController::class, 'saveImage'])->name('acara.save-image');
    Route::get('preview/html/{undangan}', [\App\Http\Controllers\Admin\ProgramController::class, 'previewHtml'])->name('preview.html');

    Route::post('template/update-body/{id}', [\App\Http\Controllers\Admin\TemplateLetterController::class, 'updateBody'])->name('template.update-body');

    Route::get('doa', [\App\Http\Controllers\Admin\WishController::class, 'index'])->name('doa.index');
    Route::get('doa/{id}', [\App\Http\Controllers\Admin\WishController::class, 'show'])->name('doa.show');
    Route::post('doa/{id}', [\App\Http\Controllers\Admin\WishController::class, 'destroy'])->name('doa.destroy');
});

Route::get('/{id}/{undangan}', [\App\Http\Controllers\PreviewController::class, 'preview'])->name('preview');

require __DIR__.'/auth.php';
