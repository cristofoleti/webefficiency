<?php
Route::get('auth/login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', ['as' => 'auth.logout', 'uses' => 'Auth\AuthController@getLogout']);

Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::get('/process-file', ['uses' => 'HomeController@processFile']);

Route::group(['middleware' => ['auth', 'company']], function() {

    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);

    Route::get('dashboard/{company_id}', ['as' => 'dashboard_data', 'uses' => 'ReadingController@dashboard']);

    Route::get('charts/variables', ['as' => 'charts.variables', 'uses' => 'ChartsController@variables']);
    Route::get('charts/variables/data/{tag}', ['as' => 'charts.variables.data', 'uses' => 'ChartsController@variablesData']);

    Route::get('/company/users/{company_id}', ['as' => 'company.users', 'uses' => 'CompaniesController@companyUsers']);
    Route::get('/company/{group_id}/{company_id}', ['as' => 'company', 'uses' => 'CompaniesController@company']);
    Route::resource('companies', 'CompaniesController');

    Route::get('/reports', ['as' => 'reports', 'uses' => 'ReportsController@index']);
    Route::post('/reports/variables', ['as' => 'reports.data.variables', 'uses' => 'ReportsController@variables']);
    Route::get('/reports/variables', ['as' => 'reports.index.variables', 'uses' => 'ReportsController@variablesIndex']);
    Route::post('/reports/performance', ['as' => 'reports.data.performance', 'uses' => 'ReportsController@performance']);
    Route::get('/reports/performance', ['as' => 'reports.index.performance', 'uses' => 'ReportsController@performanceIndex']);
    Route::get('/reports/performance/graph', ['as' => 'reports.data.performance.graph', 'uses' => 'ReportsController@performanceGraph']);
    
    Route::get('/reports/{report}', ['as' => 'reports.report_chart', 'uses' => 'ReportsController@reportChart']);
    Route::get('/reports/graph/{report}', ['as' => 'reports.report_chart.graph', 'uses' => 'ReportsController@reportChartGraph']);

    Route::post('/export/generate', ['as' => 'export.generate', 'uses' => 'ExportController@generate']);
    Route::get('export/download/{file}', ['as' => 'export.download', 'uses' => 'ExportController@download']);

    Route::resource('users', 'UsersController');
    Route::resource('groups', 'GroupsController');

});
