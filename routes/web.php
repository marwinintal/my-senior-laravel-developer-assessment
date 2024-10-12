<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Services\UserService;
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
Route::get('/phpinfo', function () {
    $userService = new UserService();
    $users = $userService->list(10, 1);

    $users = User::paginate(5, ['*'], 'page', 1);
    return phpinfo();
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resource routes for users
    Route::softDeletes('users', UserController::class);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
