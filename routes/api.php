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

Route::group(['middleware'=>'cors','prefix' => 'v1'], function (){
    Route::get('/consultores', 'ConsultoresController@consultores');
    //Route::get('/receta/{mont1_start}/{age1_end}/{mont2_inicio}/{age2_inicio}/{co_usuario}', 'ConsultoresController@recetaLiquida');
    Route::get('/consolidado/{consultor?}/{fecha_inicio}/{fecha_fin}', 'ConsultoresController@consolidadoConsultor');
    Route::get('/consolidado/grafica/{consultor?}/{fecha_inicio}/{fecha_fin}', 'ConsultoresController@consolidadoConsultorGraficas');
});

