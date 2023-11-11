<?php

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
//define the authentication routes
Auth::routes();

//Group routes under a specific namespaces and apply the 'auth' middleware to them.
Route::namespace('App\Http\Controllers')->middleware(['auth'])->group(function () {
    
    //Define a route for the root URL and associate it with a closure function
    Route::get('/', function () {
        // Return the dashboard view when this route is accessed
        return view('dashboard');
    }); //
    
    // Define a GET route for '/dashboard' that maps to the 'index' method of the 'DashboardController'
    Route::get('/dashboard', 'DashboardController@index');
    // Route::get('/system-management/{option}', 'SystemMgmtController@index');
    
    Route::get('/profile', 'ProfileController@index');
    // Define a post route for 'user-management/search' with the name 'user-management.search'
    // this route maps to the 'search' method of the 'UserManagementController'.
    Route::post('user-management/search', 'UserManagementController@search')->name('user-management.search');
    Route::resource('user-management', 'UserManagementController');
    
    Route::resource('employee-management', 'EmployeeManagementController');
    Route::post('employee-management/search', 'EmployeeManagementController@search')->name('employee-management.search');
    
    Route::resource('system-management/department', 'DepartmentController');
    Route::post('system-management/department/search', 'DepartmentController@search')->name('department.search');
    
    Route::resource('system-management/division', 'DivisionController');
    Route::post('system-management/division/search', 'DivisionController@search')->name('division.search');
    
    Route::resource('system-management/country', 'CountryController');
    Route::post('system-management/country/search', 'CountryController@search')->name('country.search');
    
    Route::resource('system-management/state', 'StateController');
    Route::post('system-management/state/search', 'StateController@search')->name('state.search');
    
    Route::resource('system-management/city', 'CityController');
    Route::post('system-management/city/search', 'CityController@search')->name('city.search');
    
    Route::get('system-management/report', 'ReportController@index');
    Route::post('system-management/report/search', 'ReportController@search')->name('report.search');
    Route::post('system-management/report/excel', 'ReportController@exportExcel')->name('report.excel');
    Route::post('system-management/report/pdf', 'ReportController@exportPDF')->name('report.pdf');
    
    Route::get('avatars/{name}', 'EmployeeManagementController@load');
        
});

