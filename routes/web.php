<?php

Route::get('/', 'TicketController@create');

// Home route with redirection based on permissions
Route::get('/home', function () {
    $route = Gate::denies('dashboard_access') ? 'admin.tickets.index' : 'admin.home';
    return redirect()->route($route)->with('status', session('status'));
});

Auth::routes(['register' => false]);

// Tickets routes
Route::post('tickets/media', 'TicketController@storeMedia')->name('tickets.storeMedia');
Route::post('tickets/comment/{ticket}', 'TicketController@storeComment')->name('tickets.storeComment');
Route::resource('tickets', 'TicketController')->only(['show', 'create', 'store']);

// Admin routes
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    // Resource routes with mass destroy for Permissions, Roles, Users, Statuses, Priorities, Categories, Comments
    $resources = [
        'permissions' => 'PermissionsController',
        'roles' => 'RolesController',
        'users' => 'UsersController',
        'statuses' => 'StatusesController',
        'priorities' => 'PrioritiesController',
        'categories' => 'CategoriesController',
        'comments' => 'CommentsController',
    ];

    foreach ($resources as $name => $controller) {
        Route::delete("{$name}/destroy", "{$controller}@massDestroy")->name("{$name}.massDestroy");
        Route::resource($name, $controller);
    }

    // Tickets specific routes
    Route::delete('tickets/destroy', 'TicketsController@massDestroy')->name('tickets.massDestroy');
    Route::post('tickets/media', 'TicketsController@storeMedia')->name('tickets.storeMedia');
    Route::post('tickets/comment/{ticket}', 'TicketsController@storeComment')->name('tickets.storeComment');
    Route::resource('tickets', 'TicketsController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
});
