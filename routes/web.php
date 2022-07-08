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

Route::get('/', 'HomeController@welcome');
Route::post('/get-data-nop', 'HomeController@getdata_nop')->name('getdata_nop');
Route::post('/simpan-data-json', 'HomeController@simpandatajson')->name('simpandatajson');
Route::post('/simpan-ubah-json', 'HomeController@simpanubahjson')->name('simpanubahjson');
Route::post('/ubah-data-pro', 'HomeController@ubahdatapro')->name('ubahdatapro');
Route::post('/simpan-data-json-baru', 'HomeController@simpandatajsonbaru')->name('simpandatajsonbaru');
Route::post('/hapus-layer', 'HomeController@hapus_layer')->name('hapus_layer');
Route::post('/get-legal-parsel', 'HomeController@getlegalparsel')->name('getlegalparsel');
Route::post('/get-blok-data', 'HomeController@getblokdata')->name('getblokdata');
Route::post('/unggahdata', 'HomeController@unggahdata')->name('unggahdata');









Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware'=>'auth'], function()
{
	Route::group(['middleware'=>'Adminarea'], function()
	{

	});
	
});


