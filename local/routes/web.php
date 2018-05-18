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


Auth::routes();

Route::get('auth/google', 'Auth\AuthController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\AuthController@handleGoogleCallback');

/* route page  */


Route::prefix('/')->group(function () {
  Route::get('/', 'UserController@index')->name('home');
  Route::get('/about', function(){
    return view('pages.about');
  });
  Route::get('/home', 'UserController@index')->name('home');
  Route::get('report','doReportController@indexReport');
  Route::get('fac-report','doReportController@facReport');
  Route::get('dep-report','doReportController@depReport');
  Route::get('report/{selectLvl}/{id}/{task}/print','doReportController@indexPrint');
  //Route::post('{taskid}/file','UserController@getFileById');
  Route::any('album/dept/std-portfolio','StudentPortfolioController@showAlbums');
  Route::any('album/dept/std-portfolio/{albumid}','StudentPortfolioController@showAlbum');
  Route::any('album/{level}/std-portfolio/all','StudentPortfolioController@getAlbumforReport');
  
  Route::get('file/{task}/user','UserController@showUserDocs');
  Route::any('file/{uid}/{task}/all','UserController@getFileByUid');
  Route::any('file/fac/{facId}/{task}','doReportController@getFileByFaculty');
  Route::any('file/dep/{depId}/{task}','doReportController@getFileByDepartment');

  Route::get('research-devinv','UserController@indexRSD');
  Route::get('research-devinv/view/{id?}','UserController@viewRSD');
  Route::get('research-devinv/add','UserController@addFormRSD');
  Route::post('research-devinv/add/save','ActionController@saveFormRSD');
  Route::get('research-devinv/edit/{id}','UserController@editFormRSD');
  Route::post('research-devinv/saveEdit','ActionController@saveEditRSD');
  Route::get('research-devinv/delete/{id}','ActionController@deleteRSD');
  Route::get('research-devinv/report','UserController@reportRSD');
  Route::get('research-devinv/print','UserController@printRSD');

  Route::get('academic-dev','UserController@indexACD');
  Route::get('academic-dev/view/{id?}','UserController@viewACD');
  Route::get('academic-dev/add','UserController@addFormACD');
  Route::post('academic-dev/add/save','ActionController@saveFormACD');
  Route::get('academic-dev/edit/{id}','UserController@editFormACD');
  Route::post('academic-dev/saveEdit','ActionController@saveEditACD');
  Route::get('academic-dev/delete/{id}','ActionController@deleteACD');
  Route::get('academic-dev/report','UserController@reportACD');
  Route::get('academic-dev/print','UserController@printACD');

  Route::get('academic-pub','UserController@indexACP');
  Route::get('academic-pub/view/{id?}','UserController@viewACP');
  Route::get('academic-pub/add/{type?}','UserController@addFormACP');
  Route::post('academic-pub/add/save/{type}','ActionController@saveFormACP');
  Route::get('academic-pub/edit/{id}','UserController@editFormACP');
  Route::post('academic-pub/saveEdit/{type}','ActionController@saveEditACP');
  Route::get('academic-pub/delete/{id}','ActionController@deleteACP');
  Route::get('academic-pub/report','UserController@reportACP');
  Route::get('academic-pub/print','UserController@printACP');

  Route::get('academic-service','UserController@indexAS');
  Route::get('academic-service/view/{id?}','UserController@viewAS');
  Route::get('academic-service/add','UserController@addFormAS');
  Route::post('academic-service/add/save','ActionController@saveFormAS');
  Route::get('academic-service/edit/{id}','UserController@editFormAS');
  Route::post('academic-service/saveEdit','ActionController@saveEditAS');
  Route::get('academic-service/delete/{id}','ActionController@deleteAS');
  Route::get('academic-service/report','UserController@reportAS');
  Route::get('academic-service/print','UserController@printAS');

  Route::get('training','UserController@indexTRN');
  Route::get('training/view/{id?}','UserController@viewTRN');
  Route::get('training/add','UserController@addFormTRN');
  Route::post('training/add/save','ActionController@saveFormTRN');
  Route::get('training/edit/{id}','UserController@editFormTRN');
  Route::post('training/saveEdit','ActionController@saveEditTRN');
  Route::get('training/delete/{id}','ActionController@deleteTRN');
  Route::get('training/report','UserController@reportTRN');
  Route::get('training/print','UserController@printTRN');

  Route::get('std-portfolio','StudentPortfolioController@indexSTP');
  Route::get('std-portfolio/view/{id?}','StudentPortfolioController@viewSTP');
  Route::get('std-portfolio/add','StudentPortfolioController@addFormSTP');
  Route::post('std-portfolio/add/save','ActionController@saveFormSTP');
  Route::get('std-portfolio/edit/{id}','StudentPortfolioController@editFormSTP');
  Route::post('std-portfolio/saveEdit','ActionController@saveEditSTP');
  Route::get('std-portfolio/delete/{id}','ActionController@deleteSTP');
  Route::get('std/{id}','StudentPortfolioController@stdList');
  Route::get('std-portfolio/report','StudentPortfolioController@reportSTP');
  Route::get('std-portfolio/print','StudentPortfolioController@printSTP');



  Route::post('deleteFile/{fileId}','ActionController@deleteFile');
  Route::get('permise','UserController@permise');
});

Route::prefix('admin')->group(function () {
  Route::get('/', 'AdminController@indexAdmin');
  Route::any('album/all/std-portfolio','AdminController@showAlbums');
  Route::any('album/all/std-portfolio/{albumid}','AdminController@showAlbum');

  Route::get('user-manage','AdminController@indexUserManage');
  Route::get('user-manage/view/{uid}','AdminController@viewUser');
  Route::get('user-manage/add','AdminController@addUser');
  Route::post('user-manage/add','AdminController@saveUser');
  Route::get('user-manage/edit/{id}','AdminController@editUser');
  Route::get('user-manage/delete/{id}','AdminController@deleteUser');
  Route::post('user-manage/saveedit','AdminController@saveeditUser');
  Route::get('user-manage/report','AdminController@testReport');

  Route::get('dep-manage','AdminController@indexDep');
  Route::get('dep-manage/add','AdminController@addDep');
  Route::post('dep-manage/add','AdminController@saveDep');
  Route::get('dep-manage/edit/{id}','AdminController@editDep');
  Route::post('dep-manage/saveedit','AdminController@saveeditDep');
  Route::get('dep-manage/delete/{id}','AdminController@deleteDep');

  Route::get('fac-manage','AdminController@indexFac');
  Route::get('fac-manage/add','AdminController@addFac');
  Route::post('fac-manage/add','AdminController@saveFac');
  Route::get('fac-manage/edit/{id}','AdminController@editFac');
  Route::post('fac-manage/saveedit','AdminController@saveeditFac');
  Route::get('fac-manage/delete/{id}','AdminController@deleteFac');



  Route::get('file/{task}/all','AdminController@showDocs');

  Route::post('checkUser','AdminController@checkUser'); 
  Route::post('checkOriginal-email/{uid}','AdminController@checkOriginalEmail'); 
});
