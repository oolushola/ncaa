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
//Clear config cache:
Route::get('/config-cache', function() {
	$exitCode = Artisan::call('config:cache');
	return 'Config cache cleared';
}); 

// Clear application cache:
Route::get('/clear-cache', function() {
	$exitCode = Artisan::call('cache:clear');
	return 'Application cache cleared';
});

// Clear view cache:
Route::get('/view-clear', function() {
	$exitCode = Artisan::call('view:clear');
	return 'View cache cleared';
});


Route::get('/', function(){
    return view('auth.login');
});
Route::get('dashboard', 'loginController@dashboard');

Route::get('aircraft-list/{aocholder}/{aocid}/{acmaker}/{acmakerid}', 'aoccontroller@getAocholderAircraftbyMake');
Route::resource('new-aoc', 'aoccontroller');
Route::post('storeaoc', 'aoccontroller@store');
Route::get('view-all-aoc', 'aoccontroller@viewallaoc');
Route::get('assign-operation-type-to-aoc', 'aoccontroller@assignOperationTypeToAoc');
Route::post('getlistings', 'aoccontroller@getListings');
Route::post('assign-operation-type-to-aoc', 'aoccontroller@insertAssignedOperation');
Route::post('remove-operation-type-from-aoc', 'aoccontroller@removeOperation');

Route::get('{aocholder}/aircrafts/{id}', 'aoccontroller@assignAirCraftType');
Route::post('addaocaircraftmake', 'aoccontroller@storeAocAircraftMake');

Route::resource('aircraft-make', 'aircraftmakecontroller');
Route::resource('operations', 'operationTypeController');

Route::get('view-all-air-craft-status', 'aircraftcontroller@viewallaircraft');
Route::post('get-aircraft-make/{id}', 'aircraftcontroller@getAircraftMakebyAoc');
Route::post('getallaircraftsbyaoc', 'aircraftcontroller@getallaircraftsbyaoc');
Route::resource('add-new-aircraft', 'aircraftcontroller');

Route::get('amo-local/view-all.html', 'amolocalcontroller@viewall');
Route::post('getlocalamobyactivestatus', 'amolocalcontroller@getlocalamobystatus');
Route::post('getlocalamobyexpiredstatus', 'amolocalcontroller@getlocalamobyexpiredstatus');
Route::post('getlocalamobyexpiringstatus', 'amolocalcontroller@getlocalamobyexpiringstatus');
Route::resource('amo-local', 'amolocalcontroller');

Route::get('amo-foreign/view-all.html', 'amoforeigncontroller@viewall');
Route::post('getforeignamobyactivestatus', 'amoforeigncontroller@getforeignamobyactivestatus');
Route::post('getforeignamobyexpiredstatus', 'amoforeigncontroller@getforeignamobyexpiredstatus');
Route::post('getforeignamobyexpiringstatus', 'amoforeigncontroller@getforeignamobyexpiringstatus');
Route::resource('amo-foreign', 'amoforeigncontroller');
Route::get('amo-view-selection', 'amocontroller@viewselection');

Route::post('aircrafttypebyaoc', 'focccontroller@getaircrafttypebyaoc');
Route::post('aircraft-reg-no', 'focccontroller@getaircraftregnumber');
Route::get('focc-lists', 'focccontroller@viewall');
Route::resource('focc', 'focccontroller');

Route::get('update-profile', 'userController@profileUpdate');
Route::patch('update-profile/{id}', 'userController@updateProfile');
Route::get('user-role', 'userController@userRole');
Route::get('user-role/{id}/edit', 'userController@editUserRole');
Route::patch('user-role/{id}', 'userController@updateUserRole');
Route::get('users', 'userController@usersList');

Auth::routes();
Route::get('/superadmin', 'superAdminController@index')->name('superadmin')->middleware('superadmin');
Route::get('/admin', 'adminController@index')->name('admin')->middleware('admin');
Route::get('/user', 'userController@index')->name('user')->middleware('users');

Route::get('activity-log/{module}', 'userController@activitylogbymodules');
Route::get('activity-log/{module}/{actual}/{id}', 'userController@activitylogbyactual');

// Route::get('/home', 'HomeController@index')->name('home');
