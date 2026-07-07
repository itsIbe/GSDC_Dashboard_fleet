<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuditLogController;



/*
|--------------------------------------------------------------------------
| Guest Routes (Only for not-logged-in users)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Root redirects to login
    Route::get('/', function () {
        return redirect('/login');
    });
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');


Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])
    ->name('profile.updatePhoto');

/*
|--------------------------------------------------------------------------
| Auth Routes (Only for logged-in users)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Home page
    Route::get('/home', function () {
        return view('home');
    });

    // Fleet capacity dashboard page
    Route::get('/fleetcapacitydashboard', function () {
        return view('fleetcap');
    });

    // Driver capacity dashboard page
    Route::get('/drivercapacitydashboard', function () {
        return view('drivercap');
    });

    // Truck, trailer, driver page
    Route::get('/trucktrailerdriver', function () {
        return view('tdt');
    });
         // Cargo Capacity
    Route::get('/cargocapacity', function () {
        return view('cargocap');
    });
//Audit

Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.index');
 // Create New User 
    Route::get('/createuser', function () {
        return view('createuser');
    });
    // Test drive page
    Route::get('/testdriver', function () {
        return view('testdrive');
    });

    // Dashboard test page
    Route::get('/dashtest', function () {
        return view('dash');
    });
    // Auto refresh audit log
Route::get('/audit-logs/fetch', [App\Http\Controllers\AuditLogController::class, 'fetch'])->name('audit.fetch');

    // Fetch data from Python script
    Route::get('/fetch-data', function () {
        $jsonFile = storage_path('app/fetch_data.json');
        if (!file_exists($jsonFile)) {
            return response()->json(['error' => 'No data yet'], 202);
        }
        $data = file_get_contents($jsonFile);
        return response($data)->header('Content-Type', 'application/json');
    });

    // Route for testing running python script from Laravel
    Route::get('/run-test', function () {
        $output = shell_exec('python ' . base_path('python/test.py'));
        return response()->json(json_decode($output, true));
    });

    // Trial and error pages
    Route::get('/gs', function () {
        return view('gs');
    });

    Route::get('/ccd', function () {
        return view('ccd');
    });

    Route::get('/cs1', function () {
        return view('cs1');
    });
});
