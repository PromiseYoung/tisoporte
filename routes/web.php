<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

// Public Routes
Route::get('/', 'TicketController@create');

// Home route with redirection based on permissions
Route::get('/home', function () {
    // Condicional de redirección basado en permisos
    $route = Gate::denies('dashboard_access') ? 'admin.tickets.index' : 'admin.home';
    return redirect()->route($route)->with('status', session('status'));
});

// Authentication Routes (excluding registration)
Auth::routes(['register' => false]);

// Tickets Routes
Route::resource('tickets', 'TicketController')->only(['show', 'create', 'store']);
Route::post('tickets/media', 'TicketController@storeMedia')->name('tickets.storeMedia');
Route::post('tickets/comment/{ticket}', 'TicketController@storeComment')->name('tickets.storeComment');

// Admin Routes (requires authentication)
Route::prefix('admin')->name('admin.')->namespace('Admin')->middleware(['auth'])->group(function () {

    // Admin Dashboard
    Route::get('/', 'HomeController@index')->name('home');

    // Resource Routes with Mass Destroy for Permissions, Roles, Users, Statuses, Priorities, Categories, Comments
    $resources = [
        'permissions' => 'PermissionsController',
        'roles' => 'RolesController',
        'users' => 'UsersController',
        'statuses' => 'StatusesController',
        'priorities' => 'PrioritiesController',
        'categories' => 'CategoriesController',
        'comments' => 'CommentsController',
    ];

    // Resourceful routes and Mass Destroy for admin resources
    foreach ($resources as $name => $controller) {
        Route::delete("{$name}/destroy", "{$controller}@massDestroy")->name("{$name}.massDestroy");
        Route::resource($name, $controller);
    }

    // Tickets Routes for Admin
    Route::resource('tickets', 'TicketsController')->except(['create', 'store']);
    Route::delete('tickets/destroy', 'TicketsController@massDestroy')->name('tickets.massDestroy');
    Route::post('tickets/media', 'TicketsController@storeMedia')->name('tickets.storeMedia');
    Route::post('tickets/comment/{ticket}', 'TicketsController@storeComment')->name('tickets.storeComment');

    // Audit Logs (Resource Routes)
    Route::resource('audit-logs', 'AuditLogsController')->only(['index', 'show']);
});
