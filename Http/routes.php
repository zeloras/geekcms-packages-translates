<?php

Route::group(['middleware' => ['web', 'permission:' . Gcms::MAIN_ADMIN_PERMISSION], 'prefix' => getAdminPrefix('translates')], function () {
    Route::group(['middleware' => ['permission:modules_translates_admin_list']], function () {
        Route::get(DIRECTORY_SEPARATOR, 'GeekCms\Translates\Http\Controllers\AdminController@index')
            ->name('admin.translates');

        Route::get('/translate/{lang}', 'GeekCms\Translates\Http\Controllers\AdminController@list')
            ->name('admin.translates.list');
    });

    Route::group(['middleware' => ['permission:modules_translates_admin_edit']], function () {
        Route::any('/edit/{lang}', 'GeekCms\Translates\Http\Controllers\AdminController@edit')
            ->name('admin.translates.edit');

        Route::post('/save/{lang}', 'GeekCms\Translates\Http\Controllers\AdminController@save')
            ->name('admin.translates.save');
    });

    Route::group(['middleware' => ['permission:modules_translates_admin_create']], function () {
        Route::any('/create', 'GeekCms\Translates\Http\Controllers\AdminController@create')
            ->name('admin.translates.create');
    });

    Route::group(['middleware' => ['permission:modules_translates_admin_delete']], function () {
        Route::post('/delete/all', 'GeekCms\Translates\Http\Controllers\AdminController@deleteAll')
            ->name('admin.translates.delete.all');

        Route::get('/delete/{lang}', 'GeekCms\Translates\Http\Controllers\AdminController@delete')
            ->name('admin.translates.delete');
    });
});
