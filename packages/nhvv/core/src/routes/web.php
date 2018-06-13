<?php
$adminPrefix = '';
//$adminPrefix = config('site.admin_prefix');
Route::group(array('prefix' => $adminPrefix), function() {
    Route::get('nhvv/core/index/log', 'IndexController@log')->name('nhvv.core.index.log');
    Route::get('nhvv/core/index/data', 'IndexController@data')->name('nhvv.core.index.data');
    Route::get('nhvv/core/index/manage', 'IndexController@manage')->name('nhvv.core.index.manage');
    Route::get('nhvv/core/index/create', 'IndexController@create')->name('nhvv.core.index.create');
    Route::post('nhvv/core/index/add', 'IndexController@add')->name('nhvv.core.index.add');
    Route::get('nhvv/core/index/show', 'IndexController@show')->name('nhvv.core.index.show');
    Route::put('nhvv/core/index/update', 'IndexController@update')->name('nhvv.core.index.update');
    Route::get('nhvv/core/index/delete', 'IndexController@delete')->name('nhvv.core.index.delete');
    Route::get('nhvv/core/index/confirm-delete', 'IndexController@getModalDelete')->name('nhvv.core.index.confirm-delete');
});