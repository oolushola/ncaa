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

//Route::group(['middleware' => 'auth'], function() {

	Route::get('/', 'loginController@index');
	Route::get('dashboard', 'loginController@dashboard')->name('dashboard');

	Route::get('aircraft-list/{aocholder}/{aocid}/{acmaker}/{acmakerid}', 'aoccontroller@getAocholderAircraftbyMake');
	Route::resource('new-aoc', 'aoccontroller');
	Route::post('storeaoc', 'aoccontroller@store');
	Route::get('view-all-aoc', 'aoccontroller@viewallaoc');
	Route::get('assign-operation-type-to-aoc', 'aoccontroller@assignOperationTypeToAoc');
	Route::post('getlistings', 'aoccontroller@getListings');
	Route::post('assign-operation-type-to-aoc', 'aoccontroller@insertAssignedOperation');
	Route::post('remove-operation-type-from-aoc', 'aoccontroller@removeOperation');
	Route::post('aoc-by-remarks', 'aoccontroller@viewallaocbyremarks');
	Route::post('aoc-by-issued-date', 'aoccontroller@viewallaocbyissuedate');
	Route::post('aoc-by-operation', 'aoccontroller@viewallaocbyoperation');

	Route::get('{aocholder}/aircrafts/{id}', 'aoccontroller@assignAirCraftType');
	Route::post('addaocaircraftmake', 'aoccontroller@storeAocAircraftMake');

	Route::resource('aircraft-make', 'aircraftmakecontroller');
	Route::resource('operations', 'operationTypeController');

	Route::get('view-all-air-craft-status', 'aircraftcontroller@viewallaircraft');
	Route::post('get-aircraft-make/{id}', 'aircraftcontroller@getAircraftMakebyAoc');
	Route::post('get-aircraft-type/{id}', 'aircraftcontroller@getAircraftType');
	Route::post('getallaircraftsbyaoc', 'aircraftcontroller@getallaircraftsbyaoc');
	Route::post('getallaircraftsbyaocandmake', 'aircraftcontroller@getallaircraftsbyaocandmake');
	Route::resource('add-new-aircraft', 'aircraftcontroller');
	Route::resource('aircraft-type', 'aircrafttypecontroller');
	Route::post('aircraftstatusbyactive', 'aircraftcontroller@viewallactiveacstatus');
	Route::post('aircraftstatusexpiringsoon', 'aircraftcontroller@viewallexpiringsoonacstatus');
	Route::post('aircraftstatusbyexpired', 'aircraftcontroller@viewallexpiredacstatus');
	Route::post('aircraftstatusbyregmarks', 'aircraftcontroller@viewacstatusbyinascdesc');
	Route::post('get-aircraft-type-ac-status', 'aircraftcontroller@sortByAircraftTpe');

	Route::get('amo-local/view-all.html', 'amolocalcontroller@viewall');
	Route::post('getlocalamobyactivestatus', 'amolocalcontroller@getlocalamobystatus');
	Route::post('getlocalamobyexpiredstatus', 'amolocalcontroller@getlocalamobyexpiredstatus');
	Route::post('getlocalamobyexpiringstatus', 'amolocalcontroller@getlocalamobyexpiringstatus');
	Route::resource('amo-local', 'amolocalcontroller');
	Route::post('getaircrafttype/{aircraft_make_id}', 'amolocalcontroller@getaircraftype');
	Route::get('local-ratings-capabilities/{id}', 'localRatingsAndCapabilities@assign');
	Route::post('getlocalAircraftTypes', 'localRatingsAndCapabilities@listaircrafttypes');
	Route::post('addRatings', 'localRatingsAndCapabilities@storeRatings');
	Route::delete('delete-local-ratings', 'localRatingsAndCapabilities@deleteLocalRatings');
	Route::post('local-amo-sorts', 'amolocalcontroller@getsorts');


	Route::get('amo-foreign/view-all.html', 'amoforeigncontroller@viewall');
	Route::post('getforeignamobyactivestatus', 'amoforeigncontroller@getforeignamobyactivestatus');
	Route::post('getforeignamobyexpiredstatus', 'amoforeigncontroller@getforeignamobyexpiredstatus');
	Route::post('getforeignamobyexpiringstatus', 'amoforeigncontroller@getforeignamobyexpiringstatus');
	Route::resource('amo-foreign', 'amoforeigncontroller');
	Route::resource('foreign-amo-holder', 'foreignAmoHolderController');
	Route::get('assignaircrafttype-to-maker/{id}', 'foreignRatingsAndCapController@assign');
	Route::post('getAircraftTypes', 'foreignRatingsAndCapController@listaircrafttypes');
	Route::post('assignaircrafttypetoamoholder', 'foreignRatingsAndCapController@storeassign');
	Route::delete('delete-foreign-ratings', 'foreignRatingsAndCapController@deleteForeignRatings');
	Route::post('foreign-amo-sorts', 'amoforeigncontroller@getsorts');

	Route::get('amo-view-selection', 'amocontroller@viewselection');


	Route::post('aircrafttypebyaircraftmaker', 'foccAndMccController@getaircrafttypebymaker');
	Route::get('focc-lists', 'foccAndMccController@viewall');
	Route::resource('focc-and-mcc', 'foccAndMccController');
	Route::post('focc-mcc-sorts', 'foccAndMccController@sorts');
	Route::post('focc-mcc-sorts-active', 'foccAndMccController@activeStatus');
	Route::post('focc-mcc-sorts-expired', 'foccAndMccController@expiredtatus');
	Route::post('focc-mcc-sorts-expiring', 'foccAndMccController@expiringStatus');

	Route::get('update-profile', 'userController@profileUpdate');
	Route::patch('update-profile/{id}', 'userController@updateProfile');
	Route::get('user-role', 'userController@userRole');
	Route::get('user-role/{id}/edit', 'userController@editUserRole');
	Route::patch('user/{id}', 'userController@updateUser');
	Route::post('user', 'userController@addNewUser');
	Route::get('users', 'userController@usersList');
	Route::get('deny-user-access', 'userController@accessDenial');

	Auth::routes();
	Route::get('/superadmin', 'superAdminController@index')->name('superadmin')->middleware('superadmin');
	Route::get('/admin', 'adminController@index')->name('admin')->middleware('admin');
	Route::get('/user', 'userController@index')->name('user')->middleware('users');

	Route::get('activity-log/{module}', 'userController@activitylogbymodules');
	Route::get('activity-log/{module}/{actual}/{id}', 'userController@activitylogbyactual');

	Route::resource('state-of-registry', 'stateOfRegistryController');
	Route::resource('general-aviation', 'generalAviationController');
	Route::resource('foreign-registration-marks', 'foreignRegistrationMarkcController');

	Route::resource('economic-licence/aop', 'aopController');
	Route::resource('economic-licence/atl', 'atlController');
	Route::resource('economic-licence/pncf', 'pncfController');
	Route::resource('economic-licence/atol', 'atolController');
	Route::resource('economic-licence/paas', 'paasController');
	Route::resource('ato', 'atoController');

	Route::get('economice-licence/aop/view', 'aopController@show');
	Route::get('economice-licence/atl/view', 'atlController@show');
	Route::get('economice-licence/pncf/view', 'pncfController@show');
	Route::get('economice-licence/paas/view', 'paasController@show');
	Route::get('ato/view', 'atoController@show');
	Route::resource('economic-licence/atol/view', 'atolController@show');
	Route::post('filter-atol-training-organization', 'atolController@filterByTrainingOrg');
	Route::post('atol-active', 'atolController@filterActive');
	Route::post('atol-expiring-soon', 'atolController@filterExpiringSoon');
	Route::post('atol-expired', 'atolController@filterExpired');

	Route::get('aop-operator-sort', 'aopController@showByOperator');
	Route::get('aop-active', 'aopController@activeAop');
	Route::get('aop-expired', 'aopController@expiredAop');
	Route::get('aop-expiring-soon', 'aopController@expiringSoonAop');

	Route::get('atl-operator-sort', 'atlController@showByOperator');
	Route::get('atl-active', 'atlController@activeAtl');
	Route::get('atl-expired', 'atlController@expiredAtl');
	Route::get('atl-expiring-soon', 'atlController@expiringSoonAtl');

	Route::get('pncf-operator-sort', 'pncfController@showByOperator');
	Route::get('pncf-active', 'pncfController@activePncf');
	Route::get('pncf-expired', 'pncfController@expiredPncf');
	Route::get('pncf-expiring-soon', 'pncfController@expiringSoonPncf');

	Route::get('paas-operator-sort', 'paasController@showByOperator');
	Route::get('paas-active', 'paasController@activePaas');
	Route::get('paas-expired', 'paasController@expiredPaas');
	Route::get('paas-expiring-soon', 'paasController@expiringSoonPaas');

	Route::get('ato-operator-sort', 'atoController@showByOperator');
	Route::get('ato-active', 'atoController@activeAto');
	Route::get('ato-expired', 'atoController@expiredAto');
	Route::get('ato-expiring-soon', 'atoController@expiringSoonAto');


	Route::resource('training-organization', 'trainingOrganizationController');
	Route::resource('travel-agency', 'travelAgencyController');

	Route::resource('type-acceptance-certificate', 'tacController');
	Route::get('all-type-acceptance-certificate', 'tacController@showAllTac');
	Route::get('get-aircraft-models', 'tacController@aircraftModelsListing');

	Route::get('aoc-chart-result', 'dashboardController@aocResult');
	Route::get('focc-and-mcc-chart-result', 'dashboardController@foccAndMccResult');
	Route::get('ac-status-chart-result', 'dashboardController@acstatusResult');
	Route::get('foreign-amo-chart-result', 'dashboardController@foreignAmoResult');
	Route::get('local-amo-chart-result', 'dashboardController@localAmoResult');

	Route::get('aop-chart-result', 'dashboardController@aopResult');
	Route::get('atl-chart-result', 'dashboardController@atlResult');
	Route::get('pncf-chart-result', 'dashboardController@pncfResult');
	Route::get('atol-chart-result', 'dashboardController@atolResult');
	Route::get('paas-chart-result', 'dashboardController@paasResult');
	Route::get('ato-chart-result', 'dashboardController@atoResult');

	
//
// });
// Route::get('/home', 'HomeController@index')->name('home');
