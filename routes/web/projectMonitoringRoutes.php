<?php

use App\Http\Controllers\ProjectMonitoring\EndOfProjectCommentController;
use App\Http\Controllers\ProjectMonitoring\EndOfProjectController;
use App\Http\Controllers\ProjectMonitoring\EndOfProjectDownloadController;
use App\Http\Controllers\ProjectMonitoring\ExtensionOfProjectCommentController;
use App\Http\Controllers\ProjectMonitoring\ExtensionOfProjectController;
use App\Http\Controllers\ProjectMonitoring\ExtensionOfProjectDownloadController;
use App\Http\Controllers\ProjectMonitoring\MonitioringMarDownloadController;
use App\Http\Controllers\ProjectMonitoring\MonitioringQfrDownloadController;
use App\Http\Controllers\ProjectMonitoring\MonitoringEfController;
use App\Http\Controllers\ProjectMonitoring\MonitoringEfMarController;
use App\Http\Controllers\ProjectMonitoring\MonitoringEfQfrController;
use App\Http\Controllers\ProjectMonitoring\MonitoringMarCommentController;
use App\Http\Controllers\ProjectMonitoring\MonitoringQfrCommentController;
use App\Http\Controllers\ProjectMonitoring\MonitoringTrfController;
use App\Http\Controllers\ProjectMonitoring\MonitoringTrfMarController;
use App\Http\Controllers\ProjectMonitoring\MonitoringTrfQfrController;
use App\Http\Controllers\ProjectMonitoring\ResearchProgressCommentController;
use App\Http\Controllers\ProjectMonitoring\ResearchProgressController;
use App\Http\Controllers\ProjectMonitoring\ResearchProgressDownloadController;
use App\Http\Controllers\ProjectMonitoring\ResearchProgressNoFundCommentController;
use App\Http\Controllers\ProjectMonitoring\ResearchProgressNoFundController;
use App\Http\Controllers\ProjectMonitoring\ResearchProgressNoFundDownloadController;
use App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject\Form1ProjectDetailsController;
use App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject\Form3ObjectivesController;
use App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject\Form4TechnologyController;
use App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject\Form5AssessmentController;
use App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject\Form6AdditionalFundController;
use App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject\Form7BenefitsController;
use App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject\Form8ReportController;
use App\Http\Controllers\ProjectMonitoring\SubFormMar\Form1MilestoneAchievementController;
use App\Http\Controllers\ProjectMonitoring\SubFormMar\Form2ProjectAchievementController;
use App\Http\Controllers\ProjectMonitoring\SubFormMar\Form3CommentaryController;
use App\Http\Controllers\ProjectMonitoring\SubFormMar\Form4AttachmentController;
use App\Http\Controllers\ProjectMonitoring\SubFormQfr\Form2FinancialProgressController;
use App\Http\Controllers\ProjectMonitoring\SubFormQfr\Form3BudgetVariationsController;
use App\Http\Controllers\ProjectMonitoring\SubFormQfr\Form4ProposedActionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'monitoring-trf', 'as' => 'panel.monitoring-trf.'], function () {
    Route::get('/', [MonitoringTrfController::class, 'index'])->name("index");

    Route::group(['prefix' => 'mar', 'as' => 'mar.'], function () {
        Route::group(['prefix' => 'sub-form', 'as' => 'sub-form.'], function () {
            Route::resource('form1', Form1MilestoneAchievementController::class)->only(['store', 'update']);
            Route::resource('form2', Form2ProjectAchievementController::class)->only(['store', 'update']);
            Route::resource('form3', Form3CommentaryController::class)->only(['store', 'update']);
            Route::resource('form4', Form4AttachmentController::class)->only(['store', 'update']);
        });

        Route::get('/create', [MonitoringTrfMarController::class, 'create'])->name("create");
        Route::get('/{mar}', [MonitoringTrfMarController::class, 'show'])->name("show");
        Route::get('/{mar}/edit', [MonitoringTrfMarController::class, 'edit'])->name("edit");
        Route::delete('/{mar}', [MonitoringTrfMarController::class, 'destroy'])->name("delete");

        Route::get('/{mar}/comment', [MonitoringMarCommentController::class, 'edit'])->name("comment");
        Route::put('/{mar}/comment', [MonitoringMarCommentController::class, 'update'])->name("comment");

        Route::get('/{mar}/download', [MonitioringMarDownloadController::class, 'show'])->name("download");
    });

    Route::group(['prefix' => 'qfr', 'as' => 'qfr.'], function () {
        Route::group(['prefix' => 'sub-form', 'as' => 'sub-form.'], function () {
            Route::resource('form2', Form2FinancialProgressController::class)->only(['store', 'update']);
            Route::resource('form3', Form3BudgetVariationsController::class)->only(['store', 'update']);
            Route::resource('form4', Form4ProposedActionController::class)->only(['store', 'update']);
        });

        Route::get('/create', [MonitoringTrfQfrController::class, 'create'])->name("create");
        Route::get('/{qfr}', [MonitoringTrfQfrController::class, 'show'])->name("show");
        Route::get('/{qfr}/edit', [MonitoringTrfQfrController::class, 'edit'])->name("edit");
        Route::delete('/{qfr}', [MonitoringTrfQfrController::class, 'destroy'])->name("delete");

        Route::get('/{qfr}/comment', [MonitoringQfrCommentController::class, 'edit'])->name("comment");
        Route::put('/{qfr}/comment', [MonitoringQfrCommentController::class, 'update'])->name("comment");

        Route::get('/{qfr}/download', [MonitioringQfrDownloadController::class, 'show'])->name("download");
    });
});


Route::group(['prefix' => 'monitoring-ef', 'as' => 'panel.monitoring-ef.'], function () {
    Route::get('/', [MonitoringEfController::class, 'index'])->name("index");

    Route::group(['prefix' => 'mar', 'as' => 'mar.'], function () {
        Route::group(['prefix' => 'sub-form', 'as' => 'sub-form.'], function () {
            Route::resource('form1', Form1MilestoneAchievementController::class)->only(['store', 'update']);
            Route::resource('form2', Form2ProjectAchievementController::class)->only(['store', 'update']);
            Route::resource('form3', Form3CommentaryController::class)->only(['store', 'update']);
            Route::resource('form4', Form4AttachmentController::class)->only(['store', 'update']);
        });

        Route::get('/{mar}/comment', [MonitoringMarCommentController::class, 'edit'])->name("comment");
        Route::put('/{mar}/comment', [MonitoringMarCommentController::class, 'update'])->name("comment");

        Route::get('/create', [MonitoringEfMarController::class, 'create'])->name("create");
        Route::get('/{mar}', [MonitoringEfMarController::class, 'show'])->name("show");
        Route::get('/{mar}/edit', [MonitoringEfMarController::class, 'edit'])->name("edit");
        Route::delete('/{mar}', [MonitoringEfMarController::class, 'destroy'])->name("delete");

        Route::get('/{mar}/download', [MonitioringMarDownloadController::class, 'show'])->name("download");
    });

    Route::group(['prefix' => 'qfr', 'as' => 'qfr.'], function () {
        Route::group(['prefix' => 'sub-form', 'as' => 'sub-form.'], function () {
            Route::resource('form2', Form2FinancialProgressController::class)->only(['store', 'update']);
            Route::resource('form3', Form3BudgetVariationsController::class)->only(['store', 'update']);
            Route::resource('form4', Form4ProposedActionController::class)->only(['store', 'update']);
        });

        Route::get('/create', [MonitoringEfQfrController::class, 'create'])->name("create");
        Route::get('/{qfr}', [MonitoringEfQfrController::class, 'show'])->name("show");
        Route::get('/{qfr}/edit', [MonitoringEfQfrController::class, 'edit'])->name("edit");
        Route::delete('/{qfr}', [MonitoringEfQfrController::class, 'destroy'])->name("delete");

        Route::get('/{qfr}/comment', [MonitoringQfrCommentController::class, 'edit'])->name("comment");
        Route::put('/{qfr}/comment', [MonitoringQfrCommentController::class, 'update'])->name("comment");

        Route::get('/{qfr}/download', [MonitioringQfrDownloadController::class, 'show'])->name("download");
    });
});


Route::group(['prefix' => 'extension-project', 'as' => 'panel.extension-project.'], function () {
    Route::get('/', [ExtensionOfProjectController::class, 'index'])->name("index");

    Route::get('/create', [ExtensionOfProjectController::class, 'create'])->name("create");
    Route::post('/', [ExtensionOfProjectController::class, 'store'])->name("store");
    Route::get('/{application}', [ExtensionOfProjectController::class, 'show'])->name("show");
    Route::get('/{application}/edit', [ExtensionOfProjectController::class, 'edit'])->name("edit");
    Route::put('/{application}', [ExtensionOfProjectController::class, 'update'])->name("update");
    Route::delete('/{application}', [ExtensionOfProjectController::class, 'destroy'])->name("delete");

    Route::get('/{application}/download', [ExtensionOfProjectDownloadController::class, 'show'])->name("download");


    Route::get('/{application}/comment', [ExtensionOfProjectCommentController::class, 'edit'])->name("comment");
    Route::put('/{application}/comment', [ExtensionOfProjectCommentController::class, 'update'])->name("comment");
});

Route::group(['prefix' => 'research-progress', 'as' => 'panel.research-progress.'], function () {
    Route::get('/', [ResearchProgressController::class, 'index'])->name("index");

    Route::get('/create', [ResearchProgressController::class, 'create'])->name("create");
    Route::post('/', [ResearchProgressController::class, 'store'])->name("store");
    Route::get('/{report}', [ResearchProgressController::class, 'show'])->name("show");
    Route::get('/{report}/edit', [ResearchProgressController::class, 'edit'])->name("edit");
    Route::put('/{report}', [ResearchProgressController::class, 'update'])->name("update");
    Route::delete('/{report}', [ResearchProgressController::class, 'destroy'])->name("delete");

    Route::get('/{report}/download', [ResearchProgressDownloadController::class, 'show'])->name("download");

    Route::get('/{report}/comment', [ResearchProgressCommentController::class, 'edit'])->name("comment");
    Route::put('/{report}/comment', [ResearchProgressCommentController::class, 'update'])->name("comment");
});

Route::group(['prefix' => 'research-progress-no-fund', 'as' => 'panel.research-progress-no-fund.'], function () {
    Route::get('/', [ResearchProgressNoFundController::class, 'index'])->name("index");

    Route::get('/create', [ResearchProgressNoFundController::class, 'create'])->name("create");
    Route::post('/', [ResearchProgressNoFundController::class, 'store'])->name("store");
    Route::get('/{report}', [ResearchProgressNoFundController::class, 'show'])->name("show");
    Route::get('/{report}/edit', [ResearchProgressNoFundController::class, 'edit'])->name("edit");
    Route::put('/{report}', [ResearchProgressNoFundController::class, 'update'])->name("update");
    Route::delete('/{report}', [ResearchProgressNoFundController::class, 'destroy'])->name("delete");

    Route::get('/{report}/download', [ResearchProgressNoFundDownloadController::class, 'show'])->name("download");

    Route::get('/{report}/comment', [ResearchProgressNoFundCommentController::class, 'edit'])->name("comment");
    Route::put('/{report}/comment', [ResearchProgressNoFundCommentController::class, 'update'])->name("comment");
});

Route::group(['prefix' => 'end-of-project', 'as' => 'panel.end-of-project.'], function () {
    Route::get('/', [EndOfProjectController::class, 'index'])->name("index");

    Route::group(['prefix' => 'sub-form', 'as' => 'sub-form.'], function () {
        Route::resource('form1', Form1ProjectDetailsController::class)->only(['store', 'update']);
        Route::resource('form3', Form3ObjectivesController::class)->only(['store', 'update']);
        Route::resource('form4', Form4TechnologyController::class)->only(['store', 'update']);
        Route::resource('form5', Form5AssessmentController::class)->only(['store', 'update']);
        Route::resource('form6', Form6AdditionalFundController::class)->only(['store', 'update']);
        Route::resource('form7', Form7BenefitsController::class)->only(['store', 'update']);
        Route::resource('form8', Form8ReportController::class)->only(['store', 'update']);
    });

    Route::get('/create', [EndOfProjectController::class, 'create'])->name("create");
    Route::get('/{report}', [EndOfProjectController::class, 'show'])->name("show");
    Route::get('/{report}/edit', [EndOfProjectController::class, 'edit'])->name("edit");
    Route::delete('/{report}', [EndOfProjectController::class, 'destroy'])->name("delete");

    Route::get('/{report}/download', [EndOfProjectDownloadController::class, 'show'])->name("download");

    Route::get('/{report}/comment', [EndOfProjectCommentController::class, 'edit'])->name("comment");
    Route::put('/{report}/comment', [EndOfProjectCommentController::class, 'update'])->name("comment");
});
