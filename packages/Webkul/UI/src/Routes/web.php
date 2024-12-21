<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Contact\PersonController;
use Webkul\UI\Http\Controllers\ExportController;

Route::group(['middleware' => ['web', 'user']], function () {

    /**
     * DataGrid export.
     */
    Route::post('/export', [ExportController::class, 'export'])->name('ui.datagrid.export');
});
