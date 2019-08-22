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

Route::group(['middleware' => ['guest']], function () {

    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
    Route::get('/login', 'Auth\LoginController@showLoginForm');
});

Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/dashboard', 'DashboardController');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    //Supervisor
    Route::group(['middleware' => ['Supervisor']], function () {

        /*   Route::get('/persona','PersonaController@index');
    Route::post('/persona/registrar','PersonaController@store');
    Route::put('/persona/actualizar','PersonaController@update');
    Route::put('/persona/desactivar','PersonaController@desactivar');
    Route::put('/persona/activar','PersonaController@activar');
    Route::get('/persona/selectPersona','PersonaController@selectPersona'); */
    Route::get('/venta', 'VentaController@index');
    Route::get('/venta/habilitaVenta', 'VentaController@habilitaVenta');
    });

    //Modelo
    Route::group(['middleware' => ['Modelo']], function () {

    });

    //Administrador
    Route::group(['middleware' => ['Administrador']], function () {
        Route::get('/persona', 'PersonaController@index');
        Route::post('/persona/registrar', 'PersonaController@store');
        Route::put('/persona/actualizar', 'PersonaController@update');
        Route::put('/persona/desactivar', 'PersonaController@desactivar');
        Route::put('/persona/activar', 'PersonaController@activar');
        Route::get('/persona/selectPersona', 'PersonaController@selectPersona');

        Route::get('/plataforma', 'PlataformaController@index');
        Route::post('/plataforma/registrar', 'PlataformaController@store');
        Route::put('/plataforma/actualizar', 'PlataformaController@update');
        Route::put('/plataforma/desactivar', 'PlataformaController@desactivar');
        Route::put('/plataforma/activar', 'PlataformaController@activar');
        Route::get('/plataforma/selectPlataforma', 'PlataformaController@selectPlataforma');

        Route::get('/venta', 'VentaController@index');
        Route::get('/venta/acumuladoPeriodo', 'VentaController@acumuladoPeriodo');
        Route::get('/venta/mostrarPeriodos', 'VentaController@mostrarPeriodos');
        Route::get('/venta/RegistroExistente', 'VentaController@RegistroExistente');
        Route::get('/venta/tokensAcumPeriodo', 'VentaController@tokensAcumPeriodo');
        Route::get('/venta/habilitaVenta', 'VentaController@habilitaVenta');

        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/actualizar', 'VentaController@update');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::put('/venta/activar', 'VentaController@activar');

        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');

        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');

        Route::get('/variable', 'VariableController@index');
        Route::put('/variable/actualizar', 'VariableController@update');
        Route::get('/variable/selectPeriodo', 'VariableController@selectPeriodo');

    });

});

//Route::get('/home', 'HomeController@index')->name('home');
