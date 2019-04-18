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

/* -------------- PAGINAS ----------------------*/
Route::group(['namespace' => 'Painel'], function(){
    Route::get('/', 'MonitoradoController@index');
    Route::get('/janeiro', 'AgendamentoController@listaAgendamentoJaneiro');
    Route::get('/fevereiro', 'AgendamentoController@listaAgendamentoFevereiro');
    Route::get('/marco', 'AgendamentoController@listaAgendamentoMarco');
    Route::get('/abril', 'AgendamentoController@listaAgendamentoAbril');
    Route::get('/maio', 'AgendamentoController@listaAgendamentoMaio');
    Route::get('/junho', 'AgendamentoController@listaAgendamentoJunho');
    Route::get('/julho', 'AgendamentoController@listaAgendamentoJulho');
    Route::get('/agosto', 'AgendamentoController@listaAgendamentoAgosto');
    Route::get('/setembro', 'AgendamentoController@listaAgendamentoSetembro');
    Route::get('/outubro', 'AgendamentoController@listaAgendamentoOutubro');
    Route::get('/novembro', 'AgendamentoController@listaAgendamentoNovembro');
    Route::get('/dezembro', 'AgendamentoController@listaAgendamentoDezembro');
});

/* -------------- MONITORADO ----------------------*/
Route::group(['namespace' => 'Painel'], function(){
    Route::post('/cadastro', 'MonitoradoController@cadastro');
    Route::get('/lista_monitorados', 'MonitoradoController@lista_monitorados');
    Route::get('/edit_monitorado/{id}', 'MonitoradoController@edit');
    Route::post('/update/{id}', 'MonitoradoController@update');
    Route::post('/procura_monitorado/{id}', 'MonitoradoController@procura_monitorado');
    Route::get('/destroy/{id}', 'MonitoradoController@destroy');
});

/* -------------- AGENDAMENTO ----------------------*/
Route::group(['namespace' => 'Painel'], function(){
    Route::post('/cadastro_agendamento', 'AgendamentoController@store');
    Route::get('/listaagendamento', 'AgendamentoController@listaAgendamento');
    Route::get('/maio', 'AgendamentoController@listaAgendamentoMaio');
    Route::get('/destroy_agend/{id}', 'AgendamentoController@destroy');
});

/* -------------- MANUTENCAO ----------------------*/
Route::group(['namespace' => 'Painel'], function(){
    Route::post('/cadastro_manutencao', 'AgendamentoController@storeManutencao');
    Route::get('/listaagendamento', 'AgendamentoController@listaAgendamento');
    Route::get('/maio', 'AgendamentoController@listaAgendamentoMaio');
    Route::get('/destroy_agend_manu/{id}/{id}', 'AgendamentoController@destroyComManutencao');
});