<?php

$adminPrefix = config('site.admin_prefix');
Route::group(array('prefix' => $adminPrefix), function() {
    Route::get('test1/test1m1/index/data', 'IndexController@data')->name('test1.test1m1.index.data');
    Route::get('test1/test1m1/index/manage', 'IndexController@manage')->name('test1.test1m1.index.manage');
    Route::get('test1/test1m1/index/create', 'IndexController@create')->name('test1.test1m1.index.create');
    Route::post('test1/test1m1/index/add', 'IndexController@add')->name('test1.test1m1.index.add');
    Route::get('test1/test1m1/index/show', 'IndexController@show')->name('test1.test1m1.index.show');
    Route::put('test1/test1m1/index/update', 'IndexController@update')->name('test1.test1m1.index.update');
    Route::get('test1/test1m1/index/delete', 'IndexController@delete')->name('test1.test1m1.index.delete');
    Route::get('test1/test1m1/index/confirm-delete', 'IndexController@getModalDelete')->name('test1.test1m1.index.confirm-delete');
});