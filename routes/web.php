<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\changePasswordController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Models\Enrigistrement;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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

// Welcome
Volt::route('/', 'welcome');

// Login
Volt::route('/login', 'login')->name('login');

//Logout


Route::middleware('auth')->group(function () {
//    // home
    Volt::route('/home', 'dashboard.index');
//
//    // Users
    Volt::route('/users', 'users.index');
    Volt::route('/users/{user}/edit', 'users.edit');
    Volt::route('/users/create', 'users.create');
    Volt::route('/change-password', 'users.password');
    Volt::route('/users/{user}', 'users.show');
    Route::post('/users/create', [UserController::class, 'store'])->name('users.store');
  //Route::get('/users/{user}/userrole', [UserController::class, 'userrole'])->name('users.userrole');
  //Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
//
//    // Brands
//    Volt::route('/brands', 'brands.index');
//    Volt::route('/brands/{brand}/edit', 'brands.edit');
//    Volt::route('/brands/create', 'brands.create');
//
    // Categories
    Volt::route('/categories', 'categories.index');
    Volt::route('/categories/{category}/edit', 'categories.edit');
    Volt::route('/categories/create', 'categories.create');
//
//    // Products
//    Volt::route('/products', 'products.index');
//    Volt::route('/products/{product}/edit', 'products.edit');
//    Volt::route('/products/create', 'products.create');
//    
//    // Orders
//    Volt::route('/orders', 'orders.index');
//    Volt::route('/orders/{order}/edit', 'orders.edit');
//    Volt::route('/orders/create', 'orders.create');

//Route::get('change-password', [changePasswordController::class, 'index']);
Route::post('change-password', [changePasswordController::class, 'changePassword']);

//projects
Volt::route('/projects', 'projects.index');
Volt::route('/projects/create', 'projects.create')->name('projects.create');
Volt::route('/projects/{project}/edit', 'projects.edit');
Volt::route('/projects/{project}/show', 'projects.show');


//students
Volt::route('/students', 'students.index');
Volt::route('/students/create', 'students.create');
Volt::route('/students/{student}/show', 'students.show');
Volt::route('/students/{student}/edit', 'students.edit');
Volt::route('/students/{student}/print', 'students.print');



//invoice

Volt::route('/invoices', 'invoices.index');


Volt::route('/invoices/create/{student}', 'invoices.create')->name('invoices.create');
Volt::route('/invoices/{invoice}/edit', 'invoices.edit')->name('invoices.edit');
Volt::route('/invoices/{invoice}/show', 'invoices.show')->name('invoices.show');
//payment
Volt::route('/payments', 'payments.index');
Volt::route('/payments/{payment}/edit', 'payments.edit')->name('payments.edit');
Volt::route('/payments/{payment}/show', 'payments.show')->name('payments.show');
Volt::route('/payments/create', 'payments.create');


//report
Volt::route('/reports', 'report.index');


//filiers
Volt::route('/filieres', 'filieres.index');
Volt::route('/filieres/{filiere}/edit', 'filieres.edit')->name('filieres.edit');
Volt::route('/filieres/create', 'filieres.create');

//niveaux
Volt::route('/niveaux', 'niveaux.index')->name('niveaux.index');
Volt::route('/niveaux/{niveau}/edit', 'niveaux.edit')->name('niveaux.edit');
Volt::route('/niveaux/create', 'niveaux.create')->name('niveaux.create');


//Section
Volt::route('/sections', 'sections.index')->name('sections.index');
Volt::route('/sections/{section}/edit', 'sections.edit')->name('sections.edit');
Volt::route('/sections/create', 'sections.create')->name('sections.create');


//tasks
Volt::route('/tasks', 'tasks.index');
Volt::route('/tasks/{task}/edit', 'tasks.edit')->name('tasks.edit');
Volt::route('/tasks/{task}/show', 'tasks.show')->name('tasks.show');

//documents
Volt::route('/documents', 'documents.index');
Volt::route('/documents/{document}/show', 'documents.show')->name('documents.show');




//files

Route::post('/upload-files/{task}', [FileController::class, 'store'])->name('file.store');
Route::post('/document-file/{document}', [FileController::class, 'documentfile'])->name('file.documentfile');

//reportdepartment
//Volt::route('/report/department', 'report.dept');
Volt::route('/enrigistrement', 'report.enrigistrement');
Volt::route('/actorsearch', 'report.actorsearch');
//Route::get(/Enrigistrement), 'report.enrigistrement'
//Route::get('/report/department/search', [ReportController::class, 'reportdept'])->name('reportdepart.search');

//
Volt::route('/processes', 'processes.index');



//role 

Route::get('roles', [RoleController::class, 'index'])->name('admin.roles.index');
Route::get('users/{user}/roles', [UserController::class, 'role'])->name('users.role');
Route::get('roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
Route::post('roles', [RoleController::class, 'store'])->name('admin.roles.store');
Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
Route::put('roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');

//Permission
Route::get('permissions', [PermissionController::class, 'index'])->name('admin.permissions.index');
Route::get('permissions/create', [PermissionController::class, 'create'])->name('admin.permissions.create');
Route::post('permissions', [PermissionController::class, 'store'])->name('admin.permissions.store');
Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('admin.permissions.edit');
Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('admin.permissions.update');

Route::post('/roles/{role}/permissions', [RoleController::class, 'givePermission'])->name('admin.roles.permissions');
Route::delete('/roles/{role}/permissions/{permission}', [RoleController::class, 'revokePermission'])->name('admin.roles.permissions.revoke');
//Route::resource('/permissions', PermissionController::class);
Route::post('/permissions/{permission}/roles', [PermissionController::class, 'assignRole'])->name('admin.permissions.roles');
Route::delete('/permissions/{permission}/roles/{role}', [PermissionController::class, 'removeRole'])->name('admin.permissions.roles.remove');
Route::post('/users/{user}/roles', [UserController::class, 'assignRole'])->name('users.roles');
Route::delete('/users/{user}/roles/{role}', [UserController::class, 'removeRole'])->name('users.roles.remove');
Route::post('/users/{user}/permissions', [UserController::class, 'givePermission'])->name('users.permissions');
Route::delete('/users/{user}/permissions/{permission}', [UserController::class, 'revokePermission'])->name('users.permissions.revoke');
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});
});
