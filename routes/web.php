<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\CrdAdminAuthController;
use App\Http\Controllers\CrdAdmin\CrdAdminDashboardController;
use App\Http\Controllers\CrdAdmin\CompanyController;
use App\Http\Controllers\CrdAdmin\AuthorizationController as CrdAuthorizationController;

use App\Http\Controllers\UserAuthController;

use App\Http\Controllers\CustomerAdmin\DashboardController as CADashboardController;
use App\Http\Controllers\CustomerAdmin\UserController as CAUserController;
use App\Http\Controllers\CustomerAdmin\ContactController as CAContactController;
use App\Http\Controllers\CustomerAdmin\TicketController as CATicketController;
use App\Http\Controllers\CustomerAdmin\TicketCommentController as CATicketCommentController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\TicketCommentController as AdminTicketCommentController;

use App\Http\Controllers\Agent\AgentDashboardController;
use App\Http\Controllers\Agent\AgentTicketController;
use App\Http\Controllers\Agent\AgentContactController;

use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Client\ClientTicketController;

/*
|--------------------------------------------------------------------------
| CRD ADMIN AUTH
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| CRD ADMIN (PROTECTED)
|--------------------------------------------------------------------------
*/
Route::prefix('crd-admin')
    ->middleware('auth:crd_admin')
    ->group(function () {

    Route::get('/dashboard', [CrdAdminDashboardController::class, 'index'])
        ->name('crd_admin.dashboard');

    // Companies (no assume)
    Route::get('/companies', [CompanyController::class, 'index'])->name('crd-admin.companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('crd-admin.companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('crd-admin.companies.store');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('crd-admin.companies.destroy');
    Route::get('/companies/list-json', [CompanyController::class, 'listJson'])->name('crd-admin.companies.list');

    // Assume flow
    Route::get('/companies/{company}/assume/confirm', [CrdAuthorizationController::class, 'prompt'])
        ->name('crd-admin.authorization.prompt');

    Route::post('/companies/{company}/assume', [CrdAuthorizationController::class, 'assume'])
        ->name('crd-admin.authorization.assume');

    Route::post('/companies/assumed/release', [CrdAuthorizationController::class, 'release'])
        ->name('crd-admin.authorization.release');

    /*
    |--------------------------------------------------------------------------
    | 🔐 COMPANY-SCOPED (ASSUME REQUIRED)
    |--------------------------------------------------------------------------
    */
    Route::middleware('ensure.company.assumed')->group(function () {

        // Users
        Route::get('/companies/{company}/users', [CompanyController::class, 'listUsers'])
            ->name('crd-admin.company-users.index');

        Route::get('/companies/{company}/users/create', [CompanyController::class, 'createUser'])
            ->name('crd-admin.company-users.create');

        Route::post('/companies/{company}/users', [CompanyController::class, 'storeUser'])
            ->name('crd-admin.company-users.store');

        Route::get('/companies/{company}/users/{user}/edit', [CompanyController::class, 'editUser'])
            ->name('crd-admin.company-users.edit');

        Route::put('/companies/{company}/users/{user}', [CompanyController::class, 'updateUser'])
            ->name('crd-admin.company-users.update');

        Route::delete('/companies/{company}/users/{user}', [CompanyController::class, 'deleteUser'])
            ->name('crd-admin.company-users.delete');

        // Tickets
        Route::get('/companies/{company}/tickets', [CompanyController::class, 'companyTickets'])
            ->name('crd-admin.companies.tickets.index');

        Route::get('/companies/{company}/tickets/{ticket}', [CompanyController::class, 'companyTicketShow'])
            ->name('crd-admin.companies.tickets.show');

        // Contacts
        Route::get('/companies/{company}/contacts', [CompanyController::class, 'companyContacts'])
            ->name('crd-admin.companies.contacts.index');
    });
});

/*
|--------------------------------------------------------------------------
| SHARED USER AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserAuthController::class, 'login'])->name('user.login.post');

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| CUSTOMER ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'customer_admin'])
    ->prefix('customer-admin')
    ->name('customer-admin.')
    ->group(function () {

    Route::get('/dashboard', [CADashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', CAUserController::class)->except(['show']);
    Route::post('users/{user}/role', [CAUserController::class, 'assignRole'])->name('users.assign-role');

    Route::get('users/client/create', [CAUserController::class, 'createClient'])->name('users.client.create');
    Route::post('users/client', [CAUserController::class, 'storeClient'])->name('users.client.store');

    Route::resource('contacts', CAContactController::class)->except(['show']);

    Route::resource('tickets', CATicketController::class);
    Route::post('tickets/{ticket}/comments', [CATicketCommentController::class, 'store'])->name('tickets.comments.store');
    Route::delete('tickets/{ticket}/comments/{comment}', [CATicketCommentController::class, 'destroy'])->name('tickets.comments.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN (CO-ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manage agents + clients only
    Route::resource('users', AdminUserController::class)->except(['show']);

    // Tickets
    Route::resource('tickets', AdminTicketController::class);

    // Ticket assignment
    Route::post('tickets/{ticket}/assign', [AdminTicketController::class, 'assign'])->name('tickets.assign');

    // Ticket comments
    Route::post('tickets/{ticket}/comments', [AdminTicketCommentController::class, 'store'])->name('tickets.comments.store');
    Route::delete('tickets/{ticket}/comments/{comment}', [AdminTicketCommentController::class, 'destroy'])->name('tickets.comments.destroy');
});

/*
|--------------------------------------------------------------------------
| AGENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'agent'])
    ->prefix('agent')
    ->name('agent.')
    ->group(function () {

    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tickets/my', [AgentTicketController::class, 'my'])->name('tickets.my');
    Route::resource('tickets', AgentTicketController::class);

    Route::post('/tickets/{ticket}/comment', [AgentTicketController::class, 'comment'])->name('tickets.comment');
    Route::delete('/tickets/{ticket}/comment/{comment}', [AgentTicketController::class, 'deleteComment'])->name('tickets.comment.delete');
    Route::post('/tickets/{ticket}/status', [AgentTicketController::class, 'updateStatus'])->name('tickets.status');
    Route::post('/tickets/{ticket}/assign', [AgentDashboardController::class, 'assignToMe'])->name('tickets.assign');

    Route::get('/dashboard/unassigned', [AgentDashboardController::class, 'unassigned'])->name('dashboard.unassigned');
    Route::resource('contacts', AgentContactController::class);
});

/*
|--------------------------------------------------------------------------
| CLIENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {

    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    Route::get('/tickets', [ClientTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [ClientTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [ClientTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [ClientTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/comment', [ClientTicketController::class, 'comment'])->name('tickets.comment');
});
