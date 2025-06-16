<?php

use App\Http\Controllers\KpiMonitoring\MyKpi\MyKpiController;
use App\Http\Controllers\KpiMonitoring\TargetKpiController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\AnalyticalServiceLabApprovementController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\AnalyticalServiceLabController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\AnalyticalServiceLabUploadBulkController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\CommercializationApprovementController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\CommercializationController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\CommercializationUploadBulkController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\ImportedGermplasmApprovementController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\ImportedGermplasmController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\ImportedGermplasmUploadBulkController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\IPRApprovementController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\IPRController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\IPRUploadBulkController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\OutputRndApprovementController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\OutputRnDController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\OutputRnDUploadBulkController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\PublicationsApprovementController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\PublicationsController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\PublicationsUploadBulkController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\RecognitionApprovementController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\RecognitionController;
use App\Http\Controllers\KpiMonitoring\SubmitNewKpi\RecognitionUploadBulkController;
use App\Http\Controllers\KpiMonitoring\TargetKpiApprovementController;
use App\Http\Controllers\KpiMonitoring\TargetKpiDownloadController;
use App\Http\Controllers\KpiMonitoring\TargetKpiGlobalController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'publications', 'as' => 'panel.publications.'], function () {
    Route::get('/', [PublicationsController::class, 'index'])->name("index");

    Route::get('/bulk-upload', [PublicationsUploadBulkController::class, 'create'])->name("bulk-create");
    Route::post('/bulk-upload', [PublicationsUploadBulkController::class, 'store'])->name("bulk-store");

    Route::get('/create', [PublicationsController::class, 'create'])->name("create");
    Route::post('/', [PublicationsController::class, 'store'])->name("store");
    Route::get('/{publication}', [PublicationsController::class, 'show'])->name("show");
    Route::get('/{publication}/edit', [PublicationsController::class, 'edit'])->name("edit");
    Route::put('/{publication}', [PublicationsController::class, 'update'])->name("update");
    Route::delete('/{publication}', [PublicationsController::class, 'destroy'])->name("delete");

    Route::get('/{publication}/approvement', [PublicationsApprovementController::class, 'edit'])->name("approvement");
    Route::put('/{publication}/approvement', [PublicationsApprovementController::class, 'update'])->name("approvement");
});

Route::group(['prefix' => 'rnd-output', 'as' => 'panel.rnd-output.'], function () {
    Route::get('/', [OutputRnDController::class, 'index'])->name("index");

    Route::get('/bulk-upload', [OutputRnDUploadBulkController::class, 'create'])->name("bulk-create");
    Route::post('/bulk-upload', [OutputRnDUploadBulkController::class, 'store'])->name("bulk-store");

    Route::get('/create', [OutputRnDController::class, 'create'])->name("create");
    Route::post('/', [OutputRnDController::class, 'store'])->name("store");
    Route::get('/{outputrnd}', [OutputRnDController::class, 'show'])->name("show");
    Route::get('/{outputrnd}/edit', [OutputRnDController::class, 'edit'])->name("edit");
    Route::put('/{outputrnd}', [OutputRnDController::class, 'update'])->name("update");
    Route::delete('/{outputrnd}', [OutputRnDController::class, 'destroy'])->name("delete");

    Route::get('/{outputrnd}/approvement', [OutputRndApprovementController::class, 'edit'])->name("approvement");
    Route::put('/{outputrnd}/approvement', [OutputRndApprovementController::class, 'update'])->name("approvement");
});

Route::group(['prefix' => 'ipr', 'as' => 'panel.ipr.'], function () {
    Route::get('/', [IPRController::class, 'index'])->name("index");

    Route::get('/bulk-upload', [IPRUploadBulkController::class, 'create'])->name("bulk-create");
    Route::post('/bulk-upload', [IPRUploadBulkController::class, 'store'])->name("bulk-store");

    Route::get('/create', [IPRController::class, 'create'])->name("create");
    Route::post('/', [IPRController::class, 'store'])->name("store");
    Route::get('/{ipr}', [IPRController::class, 'show'])->name("show");
    Route::get('/{ipr}/edit', [IPRController::class, 'edit'])->name("edit");
    Route::put('/{ipr}', [IPRController::class, 'update'])->name("update");
    Route::delete('/{ipr}', [IPRController::class, 'destroy'])->name("delete");

    Route::get('/{ipr}/approvement', [IPRApprovementController::class, 'edit'])->name("approvement");
    Route::put('/{ipr}/approvement', [IPRApprovementController::class, 'update'])->name("approvement");
});

Route::group(['prefix' => 'commercialization', 'as' => 'panel.commercialization.'], function () {
    Route::get('/', [CommercializationController::class, 'index'])->name("index");

    Route::get('/bulk-upload', [CommercializationUploadBulkController::class, 'create'])->name("bulk-create");
    Route::post('/bulk-upload', [CommercializationUploadBulkController::class, 'store'])->name("bulk-store");

    Route::get('/create', [CommercializationController::class, 'create'])->name("create");
    Route::post('/', [CommercializationController::class, 'store'])->name("store");
    Route::get('/{commercialization}', [CommercializationController::class, 'show'])->name("show");
    Route::get('/{commercialization}/edit', [CommercializationController::class, 'edit'])->name("edit");
    Route::put('/{commercialization}', [CommercializationController::class, 'update'])->name("update");
    Route::delete('/{commercialization}', [CommercializationController::class, 'destroy'])->name("delete");

    Route::get('/{commercialization}/approvement', [CommercializationApprovementController::class, 'edit'])->name("approvement");
    Route::put('/{commercialization}/approvement', [CommercializationApprovementController::class, 'update'])->name("approvement");
});

Route::group(['prefix' => 'analytical-service-lab', 'as' => 'panel.analytical-service-lab.'], function () {
    Route::get('/', [AnalyticalServiceLabController::class, 'index'])->name("index");

    Route::get('/bulk-upload', [AnalyticalServiceLabUploadBulkController::class, 'create'])->name("bulk-create");
    Route::post('/bulk-upload', [AnalyticalServiceLabUploadBulkController::class, 'store'])->name("bulk-store");

    Route::get('/create', [AnalyticalServiceLabController::class, 'create'])->name("create");
    Route::post('/', [AnalyticalServiceLabController::class, 'store'])->name("store");
    Route::get('/{analytical}', [AnalyticalServiceLabController::class, 'show'])->name("show");
    Route::get('/{analytical}/edit', [AnalyticalServiceLabController::class, 'edit'])->name("edit");
    Route::put('/{analytical}', [AnalyticalServiceLabController::class, 'update'])->name("update");
    Route::delete('/{analytical}', [AnalyticalServiceLabController::class, 'destroy'])->name("delete");

    Route::get('/{analytical}/approvement', [AnalyticalServiceLabApprovementController::class, 'edit'])->name("approvement");
    Route::put('/{analytical}/approvement', [AnalyticalServiceLabApprovementController::class, 'update'])->name("approvement");
});

Route::group(['prefix' => 'imported-germplasm', 'as' => 'panel.imported-germplasm.'], function () {
    Route::get('/', [ImportedGermplasmController::class, 'index'])->name("index");

    Route::get('/bulk-upload', [ImportedGermplasmUploadBulkController::class, 'create'])->name("bulk-create");
    Route::post('/bulk-upload', [ImportedGermplasmUploadBulkController::class, 'store'])->name("bulk-store");

    Route::get('/create', [ImportedGermplasmController::class, 'create'])->name("create");
    Route::post('/', [ImportedGermplasmController::class, 'store'])->name("store");
    Route::get('/{germplasm}', [ImportedGermplasmController::class, 'show'])->name("show");
    Route::get('/{germplasm}/edit', [ImportedGermplasmController::class, 'edit'])->name("edit");
    Route::put('/{germplasm}', [ImportedGermplasmController::class, 'update'])->name("update");
    Route::delete('/{germplasm}', [ImportedGermplasmController::class, 'destroy'])->name("delete");

    Route::get('/{germplasm}/approvement', [ImportedGermplasmApprovementController::class, 'edit'])->name("approvement");
    Route::put('/{germplasm}/approvement', [ImportedGermplasmApprovementController::class, 'update'])->name("approvement");
});

Route::group(['prefix' => 'recognition', 'as' => 'panel.recognition.'], function () {
    Route::get('/', [RecognitionController::class, 'index'])->name("index");

    Route::get('/bulk-upload', [RecognitionUploadBulkController::class, 'create'])->name("bulk-create");
    Route::post('/bulk-upload', [RecognitionUploadBulkController::class, 'store'])->name("bulk-store");

    Route::get('/create', [RecognitionController::class, 'create'])->name("create");
    Route::post('/', [RecognitionController::class, 'store'])->name("store");
    Route::get('/{recognition}', [RecognitionController::class, 'show'])->name("show");
    Route::get('/{recognition}/edit', [RecognitionController::class, 'edit'])->name("edit");
    Route::put('/{recognition}', [RecognitionController::class, 'update'])->name("update");
    Route::delete('/{recognition}', [RecognitionController::class, 'destroy'])->name("delete");

    Route::get('/{recognition}/approvement', [RecognitionApprovementController::class, 'edit'])->name("approvement");
    Route::put('/{recognition}/approvement', [RecognitionApprovementController::class, 'update'])->name("approvement");

    // Route::get('/{fileable}/download', [RecognitionDownloadController::class, 'show'])->name("download");
});

Route::group(['prefix' => 'my-kpi', 'as' => 'panel.my-kpi.'], function () {
    Route::get('/', [MyKpiController::class, 'index'])->name("index");

    Route::get('/{mykpi}', [MyKpiController::class, 'show'])->name("show");
});

Route::group(['prefix' => 'target-kpi', 'as' => 'panel.target-kpi.'], function () {
    Route::get('/', [TargetKpiController::class, 'index'])->name("index");

    Route::group(['prefix' => 'download', 'as' => 'download.'], function () {
        Route::get('/', [TargetKpiDownloadController::class, 'index'])->name('index');
        Route::get('/{year}', [TargetKpiDownloadController::class, 'show'])->name('show');
    });

    Route::get('/create', [TargetKpiController::class, 'create'])->name("create");
    Route::post('/', [TargetKpiController::class, 'store'])->name("store");
    Route::get('/{target}', [TargetKpiController::class, 'show'])->name("show");
    Route::get('/{target}/edit', [TargetKpiController::class, 'edit'])->name("edit");
    Route::put('/{target}', [TargetKpiController::class, 'update'])->name("update");
    Route::delete('/{target}', [TargetKpiController::class, 'destroy'])->name("delete");

    Route::get('/{target}/approvement', [TargetKpiApprovementController::class, 'edit'])->name("approvement");
    Route::put('/{target}/approvement', [TargetKpiApprovementController::class, 'update'])->name("approvement");
});

Route::group(['prefix' => 'target-kpi-global', 'as' => 'panel.target-kpi-global.'], function () {
    Route::get('/', [TargetKpiGlobalController::class, 'index'])->name("index");
    Route::get('/create', [TargetKpiGlobalController::class, 'create'])->name("create");
    Route::post('/', [TargetKpiGlobalController::class, 'store'])->name("store");
    Route::get('/{target}', [TargetKpiGlobalController::class, 'show'])->name("show");
    Route::get('/{target}/edit', [TargetKpiGlobalController::class, 'edit'])->name("edit");
    Route::put('/{target}', [TargetKpiGlobalController::class, 'update'])->name("update");
    Route::delete('/{target}', [TargetKpiGlobalController::class, 'destroy'])->name("delete");
});
