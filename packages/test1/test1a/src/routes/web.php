<?php
$adminPrefix = '';
//$adminPrefix = config('site.admin_prefix');
Route::group(array('prefix' => $adminPrefix), function() {
    Route::get('test1/test1a/index/log', 'IndexController@log')->name('test1.test1a.index.log');
    Route::get('test1/test1a/index/data', 'IndexController@data')->name('test1.test1a.index.data');
    Route::get('test1/test1a/index/manage', 'IndexController@manage')->name('test1.test1a.index.manage');
    Route::get('test1/test1a/index/create', 'IndexController@create')->name('test1.test1a.index.create');
    Route::post('test1/test1a/index/add', 'IndexController@add')->name('test1.test1a.index.add');
    Route::get('test1/test1a/index/show', 'IndexController@show')->name('test1.test1a.index.show');
    Route::put('test1/test1a/index/update', 'IndexController@update')->name('test1.test1a.index.update');
    Route::get('test1/test1a/index/delete', 'IndexController@delete')->name('test1.test1a.index.delete');
    Route::get('test1/test1a/index/confirm-delete', 'IndexController@getModalDelete')->name('test1.test1a.index.confirm-delete');
});