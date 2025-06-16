<?php

use App\Http\Controllers\Documentation\DocumentationController;
use App\Http\Controllers\Documentation\DocumentationDownloadController;
use App\Http\Controllers\Documentation\DocumentationExportController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'documentation', 'as' => 'panel.documentation.'], function () {
    Route::get('/', [DocumentationController::class, 'index'])->name("index");

    Route::get('/create', [DocumentationController::class, 'create'])->name("create");
    Route::post('/', [DocumentationController::class, 'store'])->name("store");
    Route::get('/{documentation}', [DocumentationController::class, 'show'])->name("show");
    Route::get('/{documentation}/edit', [DocumentationController::class, 'edit'])->name("edit");
    Route::put('/{documentation}', [DocumentationController::class, 'update'])->name("update");
    Route::delete('/{documentation}', [DocumentationController::class, 'destroy'])->name("delete");

    Route::get('/{documentation}/download', [DocumentationExportController::class, 'show'])->name("download");

    // Route::get('/{fileable}/download', [DocumentationDownloadController::class, 'show'])->name("download");
});
