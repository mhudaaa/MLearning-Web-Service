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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pengumuman/{matkul}', 'PengumumanController@getPengumuman');
Route::get('/pengumuman/detail/{id}', 'PengumumanController@getDetailPengumuman');
Route::post('/pengumuman/delete', 'PengumumanController@deletePengumuman');
Route::post('/pengumuman/tambah', 'PengumumanController@tambahPengumuman');
Route::post('/pengumuman/edit', 'PengumumanController@editPengumuman');

Route::post('/kategori/add', 'KategoriController@addCourseSection');
Route::post('/kategori/edit', 'KategoriController@editCourseSection');
Route::post('/kategori/delete', 'KategoriController@deleteCourseSection');

Route::post('/materi/tambah', 'MateriController@tambahMateri');
Route::post('/materi/tambahDokumen', 'MateriController@tambahDokumen');
Route::post('/materi/editVideo', 'MateriController@editCourseModuleURL');

Route::get('/panduan/{level}', 'PanduanController@getPanduan');
Route::get('/panduan/detail/{id}', 'PanduanController@getPanduanDetail');

// Auth
Route::post('/user/check', 'AuthController@authCheck');
