<?php

use Illuminate\Support\Facades\Route;
use App\User;
use Facade\FlareClient\View;
use Illuminate\Http\Request;

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



// Dashboard
Route::middleware(['guest'])->group(function () {
    // end of badge info
    Route::prefix('form')->group(function () {
        Route::get('/', 'FormController@index');
        Route::post('/create', 'FormController@create');
        Route::get('/search-nik/{nik}', 'FormController@checkNIK');
        Route::post('/check-nik', function (Request $request) {
            if($request->search_nik == $request->nik){
                 return response()->json(true);
            }
            $exists = \App\Patient::where('nik', $request->nik)->exists();
            return response()->json(!$exists);
        });
    });


});

Route::middleware(['apikey'])->group(function () {

       Route::prefix('api')->group(function () {
               Route::get('/transactions', 'ApiController@transactions');
       });

});



//utility
Route::get('/run-migrations', function () {
    return Artisan::call('migrate', ["--force" => true]);
});
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('route:clear');
    // return what you want
});

// Export Data
Route::prefix('exports')->group(function () {
    Route::get('patients', 'ExportImportController@patientsExport')->name('export-patient');
});

// Import Data
Route::prefix('imports')->group(function () {
    Route::post('patients', 'ExportImportController@patientsImport')->name('import-patient');
});

// interfacing
Route::post('/interfacing-insert', 'InterfacingController@insert');
Route::get('/interfacing-get-patient/{noLab}', 'InterfacingController@getPatient');
Route::get('/interfacing-get-nolab/{analyzerId}', 'InterfacingController@getNoLab');
Route::get('/interfacing-sync/{noLab}', 'InterfacingController@syncInterfacing');
Route::get('/print-barcode', 'PrinterConfigController@index');

// API
// Route::post('api/insert_patient2', 'ApiController@insertPatient2');
Route::post('api/insert_patient', 'ApiController@insertPatient');
Route::put('api/update_patient', 'ApiController@updatePatient');
Route::get('api/get_result/{id}', 'ApiController@getResult');
Route::get('api/send_result/{id}', 'ApiController@sendResult');

