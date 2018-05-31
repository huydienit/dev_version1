<?php

$adminPrefix = config('site.admin_prefix');
Route::group(array('prefix' => $adminPrefix), function() {
    Route::get('test1/test1m/index/data', 'IndexController@data')->name('test1.test1m.index.data');
    Route::get('test1/test1m/index/manage', 'IndexController@manage')->name('test1.test1m.index.manage');
    Route::get('test1/test1m/index/create', 'IndexController@create')->name('test1.test1m.index.create');
    Route::post('test1/test1m/index/add', 'IndexController@add')->name('test1.test1m.index.add');
    Route::get('test1/test1m/index/show', 'IndexController@show')->name('test1.test1m.index.show');
    Route::put('test1/test1m/index/update', 'IndexController@update')->name('test1.test1m.index.update');
    Route::get('test1/test1m/index/delete', 'IndexController@delete')->name('test1.test1m.index.delete');
    Route::get('test1/test1m/index/confirm-delete', 'IndexController@getModalDelete')->name('test1.test1m.index.confirm-delete');
});