<?php

use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmployeeBookingController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GallerieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceAdminController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffController;
use App\Models\Booking;
use App\Models\Employee;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('index'); // On donne un nom à la route ce qui permet des appel par le nom et non par l'url

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Pour accéder à la route Employe/bookings il faudra passé le middleware authentification et isEmployee
Route::middleware('auth','isEmployee')->group(function () {
    Route::get('/employee/bookings', [EmployeeBookingController::class, 'index'])->name('employeeBooking.index');

});

Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
Route::post('bookings', [BookingController::class, 'store'])->name('booking.store');
Route::get('booking/confirmation', [BookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/services', [ServiceController::class, 'index'])->name('service.index');
Route::get('/gallerie', GallerieController::class);
Route::get('/staff', StaffController::class);
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


Route::prefix('admin')->middleware('auth','isAdmin')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');
    Route::delete('/booking/{booking}', [BookingController::class, 'destroy'])->name('booking.destroy');
    Route::get('/services/create', [ServiceAdminController::class, 'create'])->name('service.create');
    Route::get('/services', [ServiceAdminController::class, 'index'])->name('serviceAdmin.index');
    Route::post('/services', [ServiceAdminController::class, 'store'])->name('service.store');
    Route::delete('/services/{service}', [ServiceAdminController::class, 'destroy'])->name('service.destroy');
    Route::get('/services/{service}/edit', [ServiceAdminController::class, 'edit'])->name('service.edit');
    Route::put('/services/{service}', [ServiceAdminController::class, 'update'])->name('service.update');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employees/{employe}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('/employees/{employe}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/employees/{employe}/delete', [EmployeeController::class, 'destroy'])->name('employee.destroy');
});

Route::prefix('admin/employee')->middleware('auth','isAdmin')->group(function () {
    Route::get('/bookings', [EmployeeBookingController::class, 'index'])->name('EmployeeBooking.index');
});

require __DIR__.'/auth.php';
