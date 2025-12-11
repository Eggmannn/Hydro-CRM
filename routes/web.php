<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\CrdAdminAuthController;
use App\Http\Controllers\CrdAdmin\CrdAdminDashboardController;
use App\Http\Controllers\CrdAdmin\CompanyController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\CustomerAdmin\DashboardController as CADashboardController;
use App\Http\Controllers\CustomerAdmin\UserController as CAUserController;
use App\Http\Controllers\CustomerAdmin\ContactController as CAContactController;
use App\Http\Controllers\CustomerAdmin\TicketController as CATicketController;
use App\Http\Controllers\CustomerAdmin\TicketCommentController as CATicketCommentController;
use App\Http\Controllers\CrdAdmin\AuthorizationController as CrdAuthorizationController;
use App\Http\Controllers\CrdAdmin\CompanyController as CrdCompanyController;
use App\Http\Middleware\EnsureCompanyAssumed;

//SUPER ADMIN ROUTES
Route::prefix('crd-admin')->middleware('auth:crd_admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [CrdAdminDashboardController::class, 'index'])
        ->name('crd_admin.dashboard');

    //Company Management
    Route::get('/companies', [CompanyController::class, 'index'])
        ->name('crd-admin.companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])
        ->name('crd-admin.companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])
        ->name('crd-admin.companies.store');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])
        ->name('crd-admin.companies.destroy');

    //User Management under a Company
    Route::get('/companies/{company}/users/create', [CompanyController::class, 'createUser'])
        ->name('crd-admin.company-users.create');
    Route::post('/companies/{company}/users', [CompanyController::class, 'storeUser'])
        ->name('crd-admin.company-users.store');

    Route::get('/companies/{company}/users', [CompanyController::class, 'listUsers'])
        ->name('crd-admin.company-users.index');

    Route::get('/companies/{company}/users/{user}/edit', [CompanyController::class, 'editUser'])
        ->name('crd-admin.company-users.edit');
    Route::put('/companies/{company}/users/{user}', [CompanyController::class, 'updateUser'])
        ->name('crd-admin.company-users.update');
    Route::delete('/companies/{company}/users/{user}', [CompanyController::class, 'deleteUser'])
        ->name('crd-admin.company-users.delete');
    
    Route::get('/companies/{company}/tickets', [CompanyController::class, 'companyTickets'])
        ->name('crd-admin.companies.tickets.index');

    Route::get('/companies/{company}/tickets/{ticket}', [CompanyController::class, 'companyTicketShow'])
        ->name('crd-admin.companies.tickets.show');

    Route::get('/companies/{company}/contacts', [CompanyController::class, 'companyContacts'])
        ->name('crd-admin.companies.contacts.index');
    
    Route::get('/companies/list-json', [CompanyController::class, 'listJson'])
        ->name('crd-admin.companies.list');
});

//Login and logout for super admin
Route::prefix('crd-admin')->group(function () {
    Route::get('/login', [CrdAdminAuthController::class, 'showLoginForm'])
        ->name('crd_admin.login');
    Route::post('/login', [CrdAdminAuthController::class, 'login'])
        ->name('crd_admin.login.post');
});

Route::post('/crd-admin/logout', function () {
    Auth::guard('crd_admin')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/crd-admin/login');
})->name('crd-admin.logout');

// Company user route
Route::get('/login', [UserAuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [UserAuthController::class, 'login'])
    ->name('user.login.post');

Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', function () {
        return view('company.dashboard');
    })->name('dashboard');
});

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

//Customer Admin Routes (shared-login)
Route::middleware(['auth', 'customer_admin'])
    ->prefix('customer-admin')
    ->name('customer-admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [CADashboardController::class, 'index'])->name('dashboard');

        // Users (customer admin manages users in their own company)
        Route::resource('users', CAUserController::class)->except(['show']);
        Route::post('users/{user}/role', [CAUserController::class, 'assignRole'])->name('users.assign-role');

        // Contacts
        Route::resource('contacts', CAContactController::class)->except(['show']);

        // Tickets + comments
        Route::resource('tickets', CATicketController::class);
        Route::post('tickets/{ticket}/comments', [CATicketCommentController::class, 'store'])->name('tickets.comments.store');
        Route::delete('tickets/{ticket}/comments/{comment}', [CATicketCommentController::class, 'destroy'])->name('tickets.comments.destroy');
    });

//Ticket Authorization testing
Route::prefix('crd-admin')->middleware('auth:crd_admin')->group(function () {

    Route::post('/companies/{company}/assume', [CrdAuthorizationController::class, 'assume'])
        ->name('crd-admin.authorization.assume');

    Route::post('/companies/assumed/release', [CrdAuthorizationController::class, 'release'])
        ->name('crd-admin.authorization.release');

    Route::get('/companies/{company}/assume/confirm', [CrdAuthorizationController::class, 'prompt'])
        ->name('crd-admin.authorization.prompt');

    Route::middleware('ensure.company.assumed')->group(function () {
        Route::get('/companies/{company}/tickets', [CrdCompanyController::class, 'companyTickets'])
            ->name('crd-admin.companies.tickets.index');

        Route::get('/companies/{company}/tickets/{ticket}', [CrdCompanyController::class, 'companyTicketShow'])
            ->name('crd-admin.companies.tickets.show');

        Route::get('/companies/{company}/contacts', [CrdCompanyController::class, 'companyContacts'])
            ->name('crd-admin.companies.contacts.index');
    });
});