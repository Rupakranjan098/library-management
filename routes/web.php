<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicCatalogController;

Route::get('/', [PublicCatalogController::class, 'index'])->name('catalog.index');
Route::post('/books/{book}/reserve', [PublicCatalogController::class, 'reserve'])->name('catalog.reserve');
Route::post('/contact', [PublicCatalogController::class, 'contact'])->name('catalog.contact');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->name('login.post');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
    ]);

    \Illuminate\Support\Facades\Auth::login($user);

    return redirect('/dashboard');
})->name('register.post');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('authors/import', [\App\Http\Controllers\AuthorController::class, 'import'])->name('authors.import');
    Route::resource('authors', \App\Http\Controllers\AuthorController::class);
    Route::post('books/import', [\App\Http\Controllers\BookController::class, 'import'])->name('books.import');
    Route::resource('books', \App\Http\Controllers\BookController::class);
    Route::resource('borrowings', \App\Http\Controllers\BorrowRecordController::class);
    Route::post('categories/import', [\App\Http\Controllers\CategoryController::class, 'import'])->name('categories.import');
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('members', \App\Http\Controllers\MemberController::class);
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\SettingController::class, 'store'])->name('settings.store');
});
