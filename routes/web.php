<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // Redirect admin ke kelola tiket
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    
    // Redirect superadmin ke admin dashboard
    if ($user->hasRole('superadmin')) {
        return redirect()->route('admin.superadmin.dashboard');
    }
    
    // Tampilkan dashboard untuk user biasa
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ticket routes for authenticated users
    Route::resource('tickets', TicketController::class);

    // Export routes
    Route::get('/export/my-tickets', [\App\Http\Controllers\ExportController::class, 'exportMyTickets'])
        ->name('export.my-tickets');

    // Notification routes
    Route::patch('/notifications/{notification}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-read');
    Route::patch('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');

    // Ticket comment routes
    Route::post('/tickets/{ticket}/comments', [\App\Http\Controllers\TicketCommentController::class, 'store'])
        ->name('ticket-comments.store');
    Route::delete('/ticket-comments/{comment}', [\App\Http\Controllers\TicketCommentController::class, 'destroy'])
        ->name('ticket-comments.destroy');
});

// admin-only routes (can change ticket status only)
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin', \App\Http\Controllers\Admin\AdminDashboardController::class)
        ->name('admin.dashboard');
    
    // Export routes for admin
    Route::get('/admin/export', [\App\Http\Controllers\ExportController::class, 'showExportForm'])
        ->name('admin.export.form');
    Route::post('/admin/export/tickets', [\App\Http\Controllers\ExportController::class, 'exportTickets'])
        ->name('admin.export.tickets');
});

// superadmin-only routes (full dashboard, stats, management)
Route::middleware(['auth','role:superadmin'])->group(function () {
    Route::get('/super', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('admin.superadmin.dashboard');

    // user role management (superadmin only)
    Route::get('/super/users', [\App\Http\Controllers\Admin\UserRoleController::class, 'index'])
        ->name('admin.superadmin.users.index');
    Route::patch('/super/users/{user}/role', [\App\Http\Controllers\Admin\UserRoleController::class, 'update'])
        ->name('admin.superadmin.users.updateRole');

    // Export routes for superadmin
    Route::get('/admin/export', [\App\Http\Controllers\ExportController::class, 'showExportForm'])
        ->name('admin.export.form');
    Route::post('/admin/export/tickets', [\App\Http\Controllers\ExportController::class, 'exportTickets'])
        ->name('admin.export.tickets');
});

require __DIR__.'/auth.php';
