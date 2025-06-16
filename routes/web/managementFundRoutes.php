<?php

use App\Http\Controllers\ManagementFund\CommentController;
use App\Http\Controllers\ManagementFund\ExternalFundCommentController;
use App\Http\Controllers\ManagementFund\ExternalFundController;
use App\Http\Controllers\ManagementFund\ProposalDownloadController;
use App\Http\Controllers\ManagementFund\SubForm\Form1IdentificationController;
use App\Http\Controllers\ManagementFund\SubForm\Form2ObjectivesController;
use App\Http\Controllers\ManagementFund\SubForm\Form3ResearchBackgroundController;
use App\Http\Controllers\ManagementFund\SubForm\Form4ResearchApproachController;
use App\Http\Controllers\ManagementFund\SubForm\Form6BenefitsController;
use App\Http\Controllers\ManagementFund\SubForm\Form7ProjectColaborationController;
use App\Http\Controllers\ManagementFund\SubForm\Form8ExpensesEstimationController;
use App\Http\Controllers\ManagementFund\SubForm\Form9ProjectCostController;
use App\Http\Controllers\ManagementFund\TrfCommentController;
use App\Http\Controllers\ManagementFund\TrfController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'trf', 'as' => 'panel.trf.'], function () {
    Route::group(['prefix' => 'sub-form', 'as' => 'sub-form.'], function () {
        Route::resource('form1', Form1IdentificationController::class)->only(['store', 'update']);
        Route::resource('form2', Form2ObjectivesController::class)->only(['store', 'update']);
        Route::resource('form3', Form3ResearchBackgroundController::class)->only(['store', 'update']);
        Route::resource('form4', Form4ResearchApproachController::class)->only(['store', 'update']);
        Route::resource('form6', Form6BenefitsController::class)->only(['store', 'update']);
        Route::resource('form7', Form7ProjectColaborationController::class)->only(['store', 'update']);
        Route::resource('form8', Form8ExpensesEstimationController::class)->only(['store', 'update']);
        Route::resource('form9', Form9ProjectCostController::class)->only(['store', 'update']);
    });

    Route::get('/{proposal}/comment', [TrfCommentController::class, 'edit'])->name("comment");
    Route::put('/{proposal}/comment', [TrfCommentController::class, 'update'])->name("comment");

    Route::get('/', [TrfController::class, 'index'])->name("index");
    Route::get('/create', [TrfController::class, 'create'])->name("create");
    Route::post('/', [TrfController::class, 'store'])->name("store");
    Route::get('/{proposal}', [TrfController::class, 'show'])->name("show");
    Route::get('/{proposal}/edit', [TrfController::class, 'edit'])->name("edit");
    Route::delete('/{proposal}', [TrfController::class, 'destroy'])->name("delete");

    Route::get('/{proposal}/download', [ProposalDownloadController::class, 'show'])->name("download");
});

Route::group(['prefix' => 'external-fund', 'as' => 'panel.external-fund.'], function () {
    Route::group(['prefix' => 'sub-form', 'as' => 'sub-form.'], function () {
        Route::resource('form1', Form1IdentificationController::class)->only(['store', 'update']);
        Route::resource('form2', Form2ObjectivesController::class)->only(['store', 'update']);
        Route::resource('form3', Form3ResearchBackgroundController::class)->only(['store', 'update']);
        Route::resource('form4', Form4ResearchApproachController::class)->only(['store', 'update']);
        Route::resource('form6', Form6BenefitsController::class)->only(['store', 'update']);
        Route::resource('form7', Form7ProjectColaborationController::class)->only(['store', 'update']);
        Route::resource('form8', Form8ExpensesEstimationController::class)->only(['store', 'update']);
        Route::resource('form9', Form9ProjectCostController::class)->only(['store', 'update']);
    });

    Route::get('/{proposal}/comment', [ExternalFundCommentController::class, 'edit'])->name("comment");
    Route::put('/{proposal}/comment', [ExternalFundCommentController::class, 'update'])->name("comment");

    Route::get('/', [ExternalFundController::class, 'index'])->name("index");
    Route::get('/create', [ExternalFundController::class, 'create'])->name("create");
    Route::post('/', [ExternalFundController::class, 'store'])->name("store");
    Route::get('/{proposal}', [ExternalFundController::class, 'show'])->name("show");
    Route::get('/{proposal}/edit', [ExternalFundController::class, 'edit'])->name("edit");
    Route::delete('/{proposal}', [ExternalFundController::class, 'destroy'])->name("delete");

    Route::get('/{proposal}/download', [ProposalDownloadController::class, 'show'])->name("download");
});
