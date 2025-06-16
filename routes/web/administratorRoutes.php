<?php

use App\Http\Controllers\Master\ReferenceTable\DivisionController;
use App\Http\Controllers\Master\ReferenceTable\ForAreaController;
use App\Http\Controllers\Master\ReferenceTable\ForCategoryController;
use App\Http\Controllers\Master\ReferenceTable\ForGroupController;
use App\Http\Controllers\Master\ReferenceTable\OtherDocumentController;
use App\Http\Controllers\Master\ReferenceTable\PatentController;
use App\Http\Controllers\Master\ReferenceTable\PositionController;
use App\Http\Controllers\Master\ReferenceTable\ProjectStatusController;
use App\Http\Controllers\Master\ReferenceTable\PslkmController;
use App\Http\Controllers\Master\ReferenceTable\PslkmSubController;
use App\Http\Controllers\Master\ReferenceTable\ResearchClusterController;
use App\Http\Controllers\Master\ReferenceTable\ResearchTypeController;
use App\Http\Controllers\Master\ReferenceTable\SeoAreaController;
use App\Http\Controllers\Master\ReferenceTable\SeoCategoryController;
use App\Http\Controllers\Master\ReferenceTable\SeoGroupController;
use App\Http\Controllers\Master\ReferenceTable\SeoSectorController;
use App\Http\Controllers\Master\ReferenceTableController;
use App\Http\Controllers\Master\ReminderController;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\MonitoringFile\MonitoringFileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user', 'as' => 'panel.user.'], function () {
    Route::get('/', [UserController::class, 'index'])->name("index");
    Route::get('/create', [UserController::class, 'create'])->name("create");
    Route::post('/', [UserController::class, 'store'])->name("store");
    Route::get('/{user}', [UserController::class, 'show'])->name("show");
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name("edit");
    Route::put('/{user}', [UserController::class, 'update'])->name("update");
    Route::put('/{user}/credentials', [UserController::class, 'updateCredentials'])->name("update-credentials");
    Route::delete('/{user}', [UserController::class, 'destroy'])->name("delete");
});

Route::group(['prefix' => 'role', 'as' => 'panel.role.'], function () {
    Route::get('/', [RoleController::class, 'index'])->name("index");
    Route::get('/create', [RoleController::class, 'create'])->name("create");
    Route::post('/', [RoleController::class, 'store'])->name("store");
    Route::get('/{role}', [RoleController::class, 'show'])->name("show");
    Route::get('/{role}/edit', [RoleController::class, 'edit'])->name("edit");
    Route::put('/{role}', [RoleController::class, 'update'])->name("update");
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name("delete");
});

Route::group(['prefix' => 'reminder', 'as' => 'panel.reminder.'], function () {
    Route::get('/', [ReminderController::class, 'index'])->name("index");
    Route::get('/create', [ReminderController::class, 'create'])->name("create");
    Route::post('/', [ReminderController::class, 'store'])->name("store");
    Route::get('/{reminder}', [ReminderController::class, 'show'])->name("show");
    Route::get('/{reminder}/edit', [ReminderController::class, 'edit'])->name("edit");
    Route::put('/{reminder}', [ReminderController::class, 'update'])->name("update");
    Route::delete('/{reminder}', [ReminderController::class, 'destroy'])->name("delete");
});

Route::group(['prefix' => 'reference-table', 'as' => 'panel.ref-table.'], function () {
    Route::get('/', [ReferenceTableController::class, 'index'])->name("index");

    Route::group(['prefix' => 'for-area', 'as' => 'for-area.'], function () {
        Route::get('/', [ForAreaController::class, 'index'])->name("index");
        Route::get('/create', [ForAreaController::class, 'create'])->name("create");
        Route::post('/', [ForAreaController::class, 'store'])->name("store");
        Route::get('/{area}', [ForAreaController::class, 'show'])->name("show");
        Route::get('/{area}/edit', [ForAreaController::class, 'edit'])->name("edit");
        Route::put('/{area}', [ForAreaController::class, 'update'])->name("update");
        Route::delete('/{area}', [ForAreaController::class, 'destroy'])->name("delete");
    });


    Route::group(['prefix' => 'for-category', 'as' => 'for-category.'], function () {
        Route::get('/', [ForCategoryController::class, 'index'])->name("index");
        Route::get('/create', [ForCategoryController::class, 'create'])->name("create");
        Route::post('/', [ForCategoryController::class, 'store'])->name("store");
        Route::get('/{category}', [ForCategoryController::class, 'show'])->name("show");
        Route::get('/{category}/edit', [ForCategoryController::class, 'edit'])->name("edit");
        Route::put('/{category}', [ForCategoryController::class, 'update'])->name("update");
        Route::delete('/{category}', [ForCategoryController::class, 'destroy'])->name("delete");
    });


    Route::group(['prefix' => 'for-group', 'as' => 'for-group.'], function () {
        Route::get('/', [ForGroupController::class, 'index'])->name("index");
        Route::get('/create', [ForGroupController::class, 'create'])->name("create");
        Route::post('/', [ForGroupController::class, 'store'])->name("store");
        Route::get('/{group}', [ForGroupController::class, 'show'])->name("show");
        Route::get('/{group}/edit', [ForGroupController::class, 'edit'])->name("edit");
        Route::put('/{group}', [ForGroupController::class, 'update'])->name("update");
        Route::delete('/{group}', [ForGroupController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'seo-category', 'as' => 'seo-category.'], function () {
        Route::get('/', [SeoCategoryController::class, 'index'])->name("index");
        Route::get('/create', [SeoCategoryController::class, 'create'])->name("create");
        Route::post('/', [SeoCategoryController::class, 'store'])->name("store");
        Route::get('/{category}', [SeoCategoryController::class, 'show'])->name("show");
        Route::get('/{category}/edit', [SeoCategoryController::class, 'edit'])->name("edit");
        Route::put('/{category}', [SeoCategoryController::class, 'update'])->name("update");
        Route::delete('/{category}', [SeoCategoryController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'seo-group', 'as' => 'seo-group.'], function () {
        Route::get('/', [SeoGroupController::class, 'index'])->name("index");
        Route::get('/create', [SeoGroupController::class, 'create'])->name("create");
        Route::post('/', [SeoGroupController::class, 'store'])->name("store");
        Route::get('/{group}', [SeoGroupController::class, 'show'])->name("show");
        Route::get('/{group}/edit', [SeoGroupController::class, 'edit'])->name("edit");
        Route::put('/{group}', [SeoGroupController::class, 'update'])->name("update");
        Route::delete('/{group}', [SeoGroupController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'seo-area', 'as' => 'seo-area.'], function () {
        Route::get('/', [SeoAreaController::class, 'index'])->name("index");
        Route::get('/create', [SeoAreaController::class, 'create'])->name("create");
        Route::post('/', [SeoAreaController::class, 'store'])->name("store");
        Route::get('/{area}', [SeoAreaController::class, 'show'])->name("show");
        Route::get('/{area}/edit', [SeoAreaController::class, 'edit'])->name("edit");
        Route::put('/{area}', [SeoAreaController::class, 'update'])->name("update");
        Route::delete('/{area}', [SeoAreaController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'seo-sector', 'as' => 'seo-sector.'], function () {
        Route::get('/', [SeoSectorController::class, 'index'])->name("index");
        Route::get('/create', [SeoSectorController::class, 'create'])->name("create");
        Route::post('/', [SeoSectorController::class, 'store'])->name("store");
        Route::get('/{sector}', [SeoSectorController::class, 'show'])->name("show");
        Route::get('/{sector}/edit', [SeoSectorController::class, 'edit'])->name("edit");
        Route::put('/{sector}', [SeoSectorController::class, 'update'])->name("update");
        Route::delete('/{sector}', [SeoSectorController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'research-cluster', 'as' => 'research-cluster.'], function () {
        Route::get('/', [ResearchClusterController::class, 'index'])->name("index");
        Route::get('/create', [ResearchClusterController::class, 'create'])->name("create");
        Route::post('/', [ResearchClusterController::class, 'store'])->name("store");
        Route::get('/{cluster}', [ResearchClusterController::class, 'show'])->name("show");
        Route::get('/{cluster}/edit', [ResearchClusterController::class, 'edit'])->name("edit");
        Route::put('/{cluster}', [ResearchClusterController::class, 'update'])->name("update");
        Route::delete('/{cluster}', [ResearchClusterController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'research-type', 'as' => 'research-type.'], function () {
        Route::get('/', [ResearchTypeController::class, 'index'])->name("index");
        Route::get('/create', [ResearchTypeController::class, 'create'])->name("create");
        Route::post('/', [ResearchTypeController::class, 'store'])->name("store");
        Route::get('/{type}', [ResearchTypeController::class, 'show'])->name("show");
        Route::get('/{type}/edit', [ResearchTypeController::class, 'edit'])->name("edit");
        Route::put('/{type}', [ResearchTypeController::class, 'update'])->name("update");
        Route::delete('/{type}', [ResearchTypeController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'patent', 'as' => 'patent.'], function () {
        Route::get('/', [PatentController::class, 'index'])->name("index");
        Route::get('/create', [PatentController::class, 'create'])->name("create");
        Route::post('/', [PatentController::class, 'store'])->name("store");
        Route::get('/{patent}', [PatentController::class, 'show'])->name("show");
        Route::get('/{patent}/edit', [PatentController::class, 'edit'])->name("edit");
        Route::put('/{patent}', [PatentController::class, 'update'])->name("update");
        Route::delete('/{patent}', [PatentController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'other-document', 'as' => 'other-document.'], function () {
        Route::get('/', [OtherDocumentController::class, 'index'])->name("index");
        Route::get('/create', [OtherDocumentController::class, 'create'])->name("create");
        Route::post('/', [OtherDocumentController::class, 'store'])->name("store");
        Route::get('/{document}', [OtherDocumentController::class, 'show'])->name("show");
        Route::get('/{document}/edit', [OtherDocumentController::class, 'edit'])->name("edit");
        Route::put('/{document}', [OtherDocumentController::class, 'update'])->name("update");
        Route::delete('/{document}', [OtherDocumentController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'division', 'as' => 'division.'], function () {
        Route::get('/', [DivisionController::class, 'index'])->name("index");
        Route::get('/create', [DivisionController::class, 'create'])->name("create");
        Route::post('/', [DivisionController::class, 'store'])->name("store");
        Route::get('/{division}', [DivisionController::class, 'show'])->name("show");
        Route::get('/{division}/edit', [DivisionController::class, 'edit'])->name("edit");
        Route::put('/{division}', [DivisionController::class, 'update'])->name("update");
        Route::delete('/{division}', [DivisionController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'position', 'as' => 'position.'], function () {
        Route::get('/', [PositionController::class, 'index'])->name("index");
        Route::get('/create', [PositionController::class, 'create'])->name("create");
        Route::post('/', [PositionController::class, 'store'])->name("store");
        Route::get('/{position}', [PositionController::class, 'show'])->name("show");
        Route::get('/{position}/edit', [PositionController::class, 'edit'])->name("edit");
        Route::put('/{position}', [PositionController::class, 'update'])->name("update");
        Route::delete('/{position}', [PositionController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'project-status', 'as' => 'project-status.'], function () {
        Route::get('/', [ProjectStatusController::class, 'index'])->name("index");
        Route::get('/create', [ProjectStatusController::class, 'create'])->name("create");
        Route::post('/', [ProjectStatusController::class, 'store'])->name("store");
        Route::get('/{status}', [ProjectStatusController::class, 'show'])->name("show");
        Route::get('/{status}/edit', [ProjectStatusController::class, 'edit'])->name("edit");
        Route::put('/{status}', [ProjectStatusController::class, 'update'])->name("update");
        Route::delete('/{status}', [ProjectStatusController::class, 'destroy'])->name("delete");
    });


    Route::group(['prefix' => 'pslkm', 'as' => 'pslkm.'], function () {
        Route::get('/', [PslkmController::class, 'index'])->name("index");
        Route::get('/create', [PslkmController::class, 'create'])->name("create");
        Route::post('/', [PslkmController::class, 'store'])->name("store");
        Route::get('/{pslkm}', [PslkmController::class, 'show'])->name("show");
        Route::get('/{pslkm}/edit', [PslkmController::class, 'edit'])->name("edit");
        Route::put('/{pslkm}', [PslkmController::class, 'update'])->name("update");
        Route::delete('/{pslkm}', [PslkmController::class, 'destroy'])->name("delete");
    });

    Route::group(['prefix' => 'pslkm-sub', 'as' => 'pslkm-sub.'], function () {
        Route::get('/', [PslkmSubController::class, 'index'])->name("index");
        Route::get('/create', [PslkmSubController::class, 'create'])->name("create");
        Route::post('/', [PslkmSubController::class, 'store'])->name("store");
        Route::get('/{sub}', [PslkmSubController::class, 'show'])->name("show");
        Route::get('/{sub}/edit', [PslkmSubController::class, 'edit'])->name("edit");
        Route::put('/{sub}', [PslkmSubController::class, 'update'])->name("update");
        Route::delete('/{sub}', [PslkmSubController::class, 'destroy'])->name("delete");
    });
});
