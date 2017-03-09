<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/



Route::post('/login', 'LoginController@signin');
Route::post('/register', 'LoginController@register');

	 Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get('user', [
            'uses' => 'LoginController@user',
        ]);
        Route::get('/casas', 'CensoController@getAll');
        Route::post('/casas', 'CensoController@save');
        Route::get('/casas/{id}', 'CensoController@getCasa');
        Route::put('/casas/{id}', 'CensoController@updateCasa');

        /* Anio Fiscal */
        Route::get('/aniofiscal/', 'MantenimientoController@getAnios');
        Route::post('/aniofiscal/', 'MantenimientoController@saveAnios');
        /* Anio Fiscal */

        /* Periodos */
        Route::get('/periodos/', 'MantenimientoController@getPeriodos');
        Route::post('/periodos/', 'MantenimientoController@savePeriodo');
        Route::get('/periodos/{anio}', 'MantenimientoController@getPeriodo');
        
        // Route::post('/', 'MantenimientoController@saveAnios');

        /* Cuentas */
        Route::get('/cuentas/', 'MantenimientoController@getCuentas');
        Route::post('/cuentas/', 'MantenimientoController@saveCuenta');
        Route::delete('/cuentas/{id}', 'MantenimientoController@deleteCuenta');
        Route::get('/bancos/', 'MantenimientoController@getBancos');


        //TipoIngresos

         Route::get('/tipoingresos/', 'MantenimientoController@getTipoIngresos'); 
         Route::post('/tipoingresos/', 'MantenimientoController@saveIngresos'); 
         Route::put('/tipoingresos/{id}', 'MantenimientoController@updateIngresos');


        //TipoIngresos

         Route::get('/tipoegresos/', 'MantenimientoController@getTipoegresos'); 
         Route::post('/tipoegresos/', 'MantenimientoController@saveEgresos'); 
         Route::put('/tipoegresos/{id}', 'MantenimientoController@updateEgresos'); 



         //Ingresos
         Route::group(['prefix' => 'ingresos'], function () {

                Route::get('/', 'IngresosController@getIngresos');
                Route::post('/generarCobro', 'IngresosController@generarCobro');
         });
         //Fin rutas ingresos

        Route::post('/pdf', 'CensoController@pdf');

    });



