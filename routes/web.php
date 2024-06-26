<?php

use App\Http\Controllers\assign_score\AssignScoreController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\attendance\AttendanceController;
use App\Http\Controllers\config\ConfigController;
use App\Http\Controllers\pdf\PdfController;
use App\Http\Controllers\programme\ProgrammeController;
use App\Http\Controllers\report\ReportController;
use App\Http\Controllers\score_card\ScoreCardController;
use App\Http\Controllers\storage\StorageController;
use App\Http\Controllers\team_label\TeamLabelController;
use App\Http\Controllers\user_profile\UserProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Authentication
Auth::routes();
Route::get('/', [LoginController::class, 'index']);
Route::get('logout', [LoginController::class, 'logout']);
Route::group(['middleware' => 'auth'], function() {
    // Dashboard
    Route::get('home', [HomeController::class, 'index'])->name('home');

    // Attendance
    Route::resource('attendances', AttendanceController::class);

    // Assign Scores
    Route::post('assign_scores/load_scores_datatable', [AssignScoreController::class, 'load_scores_datatable'])->name('assign_scores.load_scores_datatable');
    Route::post('assign_scores/reset_scores', [AssignScoreController::class, 'reset_scores'])->name('assign_scores.reset_scores');
    Route::post('assign_scores/load_scores', [AssignScoreController::class, 'load_scores'])->name('assign_scores.load_scores');
    Route::resource('assign_scores', AssignScoreController::class);

    // key Parameters
    Route::resource('programmes', ProgrammeController::class);
    Route::resource('team_labels', TeamLabelController::class);
    Route::resource('score_cards', ScoreCardController::class);

    // User Profiles
    Route::post('user_profiles/delete_profile_pic/{user}', [UserProfileController::class, 'delete_profile_pic'])->name('user_profiles.delete_profile_pic');
    Route::post('user_profiles/update_active_profile/{user}', [UserProfileController::class, 'update_active_profile'])->name('user_profiles.update_active_profile');
    Route::get('user_profiles/active_profile', [UserProfileController::class, 'active_profile'])->name('user_profiles.active_profile');
    Route::resource('user_profiles', UserProfileController::class);

    // View Reports
    Route::get('reports/performance', [ReportController::class, 'create_performance'])->name('reports.create_performance');
    Route::post('reports/performance', [ReportController::class, 'generate_performance'])->name('reports.generate_performance');

    // PDF Report
    Route::get('pdfs/agenda/{agenda}/{token}', [PdfController::class, 'print_agenda'])->name('pdfs.print_agenda');

    // Storage
    Route::get('storage/{file_params}', [StorageController::class, 'file_render'])->name('storage.file_render');
    Route::get('storage/download/{file_params}', [StorageController::class, 'file_download'])->name('storage.file_download');

    // Configuration
    Route::get('clear-cache', [ConfigController::class, 'clear_cache'])->name('config.clear_cache');
    Route::get('site-down', [ConfigController::class, 'site_down'])->name('config.site_down');
});
