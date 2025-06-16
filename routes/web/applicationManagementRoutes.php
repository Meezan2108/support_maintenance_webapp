<?php

use App\Http\Controllers\ApplicationManagement\EvaluationController;
use App\Http\Controllers\ApplicationManagement\ListOfApprovedController;
use App\Http\Controllers\ApplicationManagement\ListOfRejectedController;
use App\Http\Controllers\ApplicationManagement\SubForm\Form1TimelineController;
use App\Http\Controllers\ApplicationManagement\SubForm\Form2ExpensesEstimationController;
use App\Http\Controllers\ApplicationManagement\SubForm\Form3ProjectCostController;
use App\Http\Controllers\ApplicationManagement\SubForm\Form4DocumentationController;
use App\Http\Controllers\ApplicationManagement\SubForm\Form5StatusController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'technical-evaluation', 'as' => 'panel.technical-evaluation.'], function () {
    Route::get('/', [EvaluationController::class, 'index'])->name("index");
    Route::get('/{proposal}', [EvaluationController::class, 'show'])->name("show");
    Route::get('/{proposal}/edit', [EvaluationController::class, 'edit'])->name("edit");
    Route::put('/{proposal}', [EvaluationController::class, 'update'])->name("update");
});

Route::group(['prefix' => 'list-of-approved', 'as' => 'panel.list-of-approved.'], function () {
    Route::group(['prefix' => 'sub-form', 'as' => 'sub-form.'], function () {
        Route::resource('form1', Form1TimelineController::class)->only(['update']);
        Route::resource('form2', Form2ExpensesEstimationController::class)->only(['update']);
        Route::resource('form3', Form3ProjectCostController::class)->only(['update']);
        Route::resource('form4', Form4DocumentationController::class)->only(['update']);
        Route::resource('form5', Form5StatusController::class)->only(['update']);
    });

    Route::get('/', [ListOfApprovedController::class, 'index'])->name("index");
    Route::get('/create', [ListOfApprovedController::class, 'create'])->name("create");
    Route::get('/{proposal}', [ListOfApprovedController::class, 'show'])->name("show");
    Route::get('/{proposal}/edit', [ListOfApprovedController::class, 'edit'])->name("edit");
    // Route::put('/{proposal}', [ListOfApprovedController::class, 'update'])->name("update");
});

Route::group(['prefix' => 'list-of-rejected', 'as' => 'panel.list-of-rejected.'], function () {
    Route::get('/', [ListOfRejectedController::class, 'index'])->name("index");
});
