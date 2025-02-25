<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Controller;
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

// RESET PASSWORD ROUTE
Route::post('password/reset', [ResetPasswordController::class, 'reset']);

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

    // Definir rutas de eliminación masiva con una URL diferente
    foreach ($resources as $name => $controller) {
        // Usa una URL específica para la eliminación masiva
        Route::delete("{$name}/mass-destroy", "{$controller}@massDestroy")->name("{$name}.massDestroy");

        // Rutas de recursos
        Route::resource($name, $controller);
    }

    // Rutas de Tickets usando `resource Admin`
    Route::resource('tickets', 'TicketsController');

    // Rutas adicionales que no están cubiertas por `resource`
    Route::delete('tickets/destroy', 'TicketsController@massDestroy')->name('tickets.massDestroy');
    Route::post('tickets/media', 'TicketsController@storeMedia')->name('tickets.storeMedia');
    Route::post('tickets/comment/{ticket}', 'TicketsController@storeComment')->name('tickets.storeComment');

    // Audit Logs (Resource Routes)
    Route::resource('audit-logs', 'AuditLogsController')->only(['index', 'show']);

    Route::get('/home', [Controller::class, 'index'])->middleware('prevent.cache');
});
