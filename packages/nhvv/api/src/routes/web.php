<?php
$apiPrefix = config('site.api_prefix');
Route::group(array('prefix' => $apiPrefix), function() {
    Route::get('version', 'SettingController@version')->name('nhvv.api.setting.version');
    Route::get('setting', 'SettingController@setting')->name('nhvv.api.setting.setting');

    Route::get('demo', 'SettingController@demo')->name('nhvv.api.setting.demo');

    Route::get('listMaterial', 'SettingController@listMaterial')->name('nhvv.api.setting.listMaterial');
    Route::get('listMaterialPackage', 'SettingController@listMaterialPackage')->name('nhvv.api.setting.listMaterialPackage');
    Route::get('listMaterialMarketTab', 'SettingController@listMaterialMarketTab')->name('nhvv.api.setting.listMaterialMarketTab');

    Route::get('listDecoration', 'SettingController@listDecoration')->name('nhvv.api.setting.listDecoration');
    Route::get('listDecorationgroup', 'SettingController@listDecorationgroup')->name('nhvv.api.setting.listDecorationgroup');
    Route::get('listDecorationTag', 'SettingController@listDecorationTag')->name('nhvv.api.setting.listDecorationTag');

    Route::get('gridData', 'SettingController@gridData')->name('nhvv.api.setting.gridData');
    Route::get('mapData', 'SettingController@mapData')->name('nhvv.api.setting.mapData');

    Route::get('listStaff', 'SettingController@listStaff')->name('nhvv.api.setting.listStaff');
    Route::get('listStaffTab', 'SettingController@listStaffTab')->name('nhvv.api.setting.listStaffTab');
    Route::get('listStaffPosition', 'SettingController@listStaffPosition')->name('nhvv.api.setting.listStaffPosition');

    Route::get('listBundle', 'SettingController@listBundle')->name('nhvv.api.setting.listBundle');
    Route::get('listFoodRecipe', 'SettingController@listFoodRecipe')->name('nhvv.api.setting.listFoodRecipe');
    Route::get('listStorageTab', 'SettingController@listStorageTab')->name('nhvv.api.setting.listStorageTab');
    Route::get('listRecipeTab', 'SettingController@listRecipeTab')->name('nhvv.api.setting.listRecipeTab');
});