<?php

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
    return redirect('/login');
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
    Route::get('/', function () {
        return view('admin.index');
    })->name('index');

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
    Route::resource('acara', \App\Http\Controllers\Admin\ProgramController::class)->only(['index', 'store']);

    Route::get('acara/{undangan}/undangan', [\App\Http\Controllers\Admin\ProgramController::class, 'templateLetterUtils'])->name('acara.template-undangan');

    Route::post('acara/save-image', [\App\Http\Controllers\Admin\ProgramController::class, 'saveImage'])->name('acara.save-image');
    Route::get('preview/html/{undangan}', [\App\Http\Controllers\Admin\ProgramController::class, 'previewHtml'])->name('preview.html');

});

require __DIR__.'/auth.php';
