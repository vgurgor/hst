<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WEB\CampusController;
use App\Http\Controllers\WEB\BranchController;
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
    return redirect('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::get('/campus', [CampusController::class, 'index'])->middleware(['auth', 'verified'])->name('campus.list');
Route::put('/campus/delete/{id}', [CampusController::class, 'delete'])->middleware(['auth', 'verified'])->name('campus.delete');
Route::get('/campuses/edit/{id}', [CampusController::class, 'edit'])->name('campus.edit');
Route::put('/campus/{id}', [CampusController::class, 'update'])->middleware(['auth', 'verified'])->name('campus.update');
Route::post('/campus/filter', [CampusController::class, 'filter'])->name('campus.filter');
Route::get('/campus/add', [CampusController::class, 'add'])->name('campus.add');
Route::post('/campus', [CampusController::class, 'store'])->name('campus.store');


Route::get('/branch', [BranchController::class, 'index'])->middleware(['auth', 'verified'])->name('branch.list');
Route::put('/branch/delete/{id}', [BranchController::class, 'delete'])->middleware(['auth', 'verified'])->name('branch.delete');
Route::get('/branch/edit/{id}', [BranchController::class, 'edit'])->name('branch.edit');
Route::put('/branch/{id}', [BranchController::class, 'update'])->middleware(['auth', 'verified'])->name('branch.update');
Route::post('/branch/filter', [BranchController::class, 'filter'])->name('branch.filter');
Route::get('/branch/add', [BranchController::class, 'add'])->name('branch.add');
Route::post('/branch', [BranchController::class, 'store'])->name('branch.store');

Route::get('change-language/{locale}', function ($locale) {
    if (!in_array($locale, ['tr', 'en'])) {
        abort(404);
    }

    session(['locale' => $locale]);

    return back();
})->name('changeLanguage');

require __DIR__.'/auth.php';
