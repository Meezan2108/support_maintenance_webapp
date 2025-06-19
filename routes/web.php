<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MonitoringFile\MonitoringFileController;
use App\Http\Controllers\MyTaskController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Resources\ExtensionProjectMilestoneController;
use App\Http\Controllers\Resources\FileableController;
use App\Http\Controllers\Resources\MilestoneController;
use App\Http\Controllers\Resources\NotificationController as ResourcesNotificationController;
use App\Http\Controllers\Resources\ProposalController;
use App\Http\Controllers\Resources\RefDivisionController;
use App\Http\Controllers\Resources\RefForAreaController;
use App\Http\Controllers\Resources\RefForCategoryController;
use App\Http\Controllers\Resources\RefForGroupController;
use App\Http\Controllers\Resources\RefPositionController;
use App\Http\Controllers\Resources\RefPslkmController;
use App\Http\Controllers\Resources\RefPslkmSubController;
use App\Http\Controllers\Resources\RefPubTypeController;
use App\Http\Controllers\Resources\RefResearchClusterController;
use App\Http\Controllers\Resources\RefResearchTypeController;
use App\Http\Controllers\Resources\RefSeoAreaController;
use App\Http\Controllers\Resources\RefSeoCategoryController;
use App\Http\Controllers\Resources\RefSeoGroupController;
use App\Http\Controllers\Resources\RefTypeOfFundingController;
use App\Http\Controllers\Resources\TaskController;
use App\Http\Controllers\Resources\UserController as ResourcesUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Added new pages
use App\Http\Controllers\LocationController;
use App\Http\Controllers\STMemberController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SupportMaintenanceController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\IssueTypeController;





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    if (config('app.env') == "production") {
        return redirect()->route('login');
    }

    return Inertia::render('Welcome');
})->name("home");

Route::get('/login', [LoginController::class, 'form'])->name("login");
Route::post('/login', [LoginController::class, 'authenticate'])
    ->middleware('recaptcha')
    ->name("login.submit");

Route::post('/logout', [AuthController::class, 'logout'])->name("logout");


Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('forgot-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('forgot-password');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'edit'])->name('reset-password');
Route::post('/reset-password', [ResetPasswordController::class, 'update'])->name('reset-password.submit');


//Master Data Locations

     Route::resource('locations', LocationController::class);


    // Route::resource('locations', LocationController::class);

// Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
// Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
// Route::resource('locations', LocationController::class);


Route::get('/st-members', [StMemberController::class, 'index'])->name('st-members.index');
Route::post('/st-members', [StMemberController::class, 'store'])->name('st-members.store');
Route::put('/st-members/{id}', [StMemberController::class, 'update']);
Route::delete('/st-members/{id}', [StMemberController::class, 'destroy']);
Route::resource('st-members', StMemberController::class);



// Route::prefix('clients')->name('clients.')->group(function () {
//     Route::get('/', [ClientController::class, 'index'])->name('index');
//     Route::get('/create', [ClientController::class, 'create'])->name('create');
//     Route::post('/', [ClientController::class, 'store'])->name('store');
//     Route::get('/{client}', [ClientController::class, 'show'])->name('show');
//     Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
//     Route::put('/{client}', [ClientController::class, 'update'])->name('update');
//     Route::delete('/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
// });

Route::prefix('clients')->name('clients.')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('index');
    Route::get('/create', [ClientController::class, 'create'])->name('create');
    Route::post('/', [ClientController::class, 'store'])->name('store');
    Route::get('/{client}', [ClientController::class, 'show'])->name('show');
    Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
    Route::put('/{client}', [ClientController::class, 'update'])->name('update');
    Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy'); // ✅ FIXED
});


Route::get('/developers', [DeveloperController::class, 'index'])->name('developers.index');
Route::post('/developers', [DeveloperController::class, 'store'])->name('developers.store');
Route::put('/developers/{developer}', [DeveloperController::class, 'update'])->name('developers.update');
Route::delete('/developers/{developer}', [DeveloperController::class, 'destroy'])->name('developers.destroy');




Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/issue-types', [IssueTypeController::class, 'index'])->name('issue-types.index');
    Route::post('/issue-types', [IssueTypeController::class, 'store'])->name('issue-types.store');
    Route::put('/issue-types/{issueType}', [IssueTypeController::class, 'update'])->name('issue-types.update');
    Route::delete('/issue-types/{issueType}', [IssueTypeController::class, 'destroy'])->name('issue-types.destroy');
});



Route::put('/projects/{project}/update-status', [ProjectController::class, 'updateStatus'])->name('projects.update-status');
Route::resource('projects', ProjectController::class);

Route::post('/support-maintenance/bulk-destroy', [SupportMaintenanceController::class, 'bulkDestroy'])
    ->name('support-maintenance.bulkDestroy');


// Route::delete('/support-maintenance/bulk-delete', [SupportMaintenanceController::class, 'bulkDestroy'])->name('support-maintenance.bulkDestroy');
Route::resource('support-maintenance', SupportMaintenanceController::class);

















if (config('app.env') != "production") {
    Route::get('/auth_callback', [AuthController::class, 'callback'])->name('auth.callback');
}

Route::get('/login-by-employee-id', [AuthController::class, 'callback'])->name('auth.by-employee-id');

Route::middleware(['auth'])->get('/trf', function () {
    return Inertia::render('Trf');
})->name('trf.page'); // Optional route nam


//Route::post('/panel/ref-table/location', [LocationController::class, 'store'])->name('panel.ref-table.location.store');



Route::group(["middleware" => "auth"], function () {

    Route::resource('location', \App\Http\Controllers\Master\ReferenceTable\LocationController::class);
    //New code added for the login part
    Route::get('/trf', function () {
        return Inertia::render('Temporary Research Fund (TRF)'); // or any Inertia page you want
    })->name('panel.trf');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('panel.dashboard');

    Route::prefix('panel/ref-table')->name('panel.ref-table.')->group(function () {
    Route::resource('location', \App\Http\Controllers\Master\ReferenceTable\LocationController::class);
});

    


    Route::get('/profile', [ProfileController::class, "show"])->name('panel.profile');
    Route::get('/profile/edit', [ProfileController::class, "edit"])->name('panel.profile.edit');
    Route::put('/profile', [ProfileController::class, "update"])->name('panel.profile.update');
    Route::put('/profile/credentials', [ProfileController::class, "updateCredentials"])->name('panel.profile.update-creds');

    Route::get('/notifications', [NotificationController::class, "index"])->name('panel.notification.index');
    Route::post('/notifications/read-all', [NotificationController::class, "updateAllAsRead"])->name('panel.notification.read-all');
    Route::put('/notifications/{notification}', [NotificationController::class, "update"])->name('panel.notification.update');


    Route::group(["middleware" => "menu.autho"], function () {

        Route::get('/my-task', [MyTaskController::class, 'index'])->name('panel.my-task.index');
        Route::delete('/my-task/{task}', [MyTaskController::class, 'destory'])->name('panel.my-task.delete');

        // ./web/managementFundRoutes.php
        Route::group([], __DIR__ . '/web/managementFundRoutes.php');

        // ./web/applicationManagementRoutes.php
        Route::group([], __DIR__ . '/web/applicationManagementRoutes.php');

        // ./web/projectMonitoringRoutes.php
        Route::group([], __DIR__ . '/web/projectMonitoringRoutes.php');

        // ./web/kpiMonitoringRoutes.php
        Route::group([], __DIR__ . '/web/kpiMonitoringRoutes.php');

        // ./web/administratorRoutes.php
        Route::group([], __DIR__ . '/web/administratorRoutes.php');

        // ./web/documentationRoutes.php
        Route::group([], __DIR__ . '/web/documentationRoutes.php');
    });


    Route::group(['prefix' => 'resources', 'as' => 'resources.'], function () {
        Route::resource('user', ResourcesUserController::class)->only(['index', 'show']);
        Route::resource('fileable', FileableController::class)->only(['show']);

        Route::resource('proposal', ProposalController::class)->only(['index', 'show']);
        Route::resource('milestone', MilestoneController::class)->only(['index', 'show']);

        Route::resource('extension-milestone', ExtensionProjectMilestoneController::class)->only(['show']);

        Route::resource('type-of-funding', RefTypeOfFundingController::class)->only(['index', 'show']);
        Route::resource('position', RefPositionController::class)->only(['index', 'show']);
        Route::resource('division', RefDivisionController::class)->only(['index', 'show']);

        Route::resource('research-type', RefResearchTypeController::class)->only(['index', 'show']);
        Route::resource('research-cluster', RefResearchClusterController::class)->only(['index', 'show']);

        Route::resource('for-category', RefForCategoryController::class)->only(['index', 'show']);
        Route::post('/new-location', [NewLocationController::class, 'store'])->name('new-location.store');
        Route::resource('for-group', RefForGroupController::class)->only(['index', 'show']);
        Route::resource('for-area', RefForAreaController::class)->only(['index', 'show']);

        Route::resource('seo-category', RefSeoCategoryController::class)->only(['index', 'show']);
        Route::resource('seo-group', RefSeoGroupController::class)->only(['index', 'show']);
        Route::resource('seo-area', RefSeoAreaController::class)->only(['index', 'show']);

        Route::resource('pub-type', RefPubTypeController::class)->only(['index', 'show']);
        Route::resource('pslkm', RefPslkmController::class)->only(['index', 'show']);
        Route::resource('pslkm-sub', RefPslkmSubController::class)->only(['index', 'show']);

        Route::group(['prefix' => 'notifications', 'as' => 'notification.'], function () {
            Route::get('/', [ResourcesNotificationController::class, 'index'])->name('index');
            Route::get('/count', [ResourcesNotificationController::class, 'count'])->name('count');
        });

        Route::get('task/count', [TaskController::class, 'count'])->name('task.count');
    });

    Route::get('/monitoring-file', [MonitoringFileController::class, "index"])->name('panel.monitoring-file.index');
});
