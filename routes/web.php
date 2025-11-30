<?php

use App\Http\Controllers\assign_score\AssignScoreController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\config\ConfigController;
use App\Http\Controllers\metric\MetricController;
use App\Http\Controllers\pdf\PdfController;
use App\Http\Controllers\programme\ProgrammeController;
use App\Http\Controllers\report\ReportController;
use App\Http\Controllers\score_card\ScoreCardController;
use App\Http\Controllers\storage\StorageController;
use App\Http\Controllers\team\TeamController;
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

    // Metrics
    Route::post('metrics/approve', [MetricController::class, 'approveMetrics'])->name('metrics.approve');
    Route::post('metrics/datatable', [MetricController::class, 'metricsDatatable'])->name('metrics.get_data');
    Route::resource('metrics', MetricController::class);

    // Assign Scores
    Route::post('assign_scores/load_scores_datatable', [AssignScoreController::class, 'load_scores_datatable'])->name('assign_scores.load_scores_datatable');
    Route::post('assign_scores/reset_scores', [AssignScoreController::class, 'reset_scores'])->name('assign_scores.reset_scores');
    Route::post('assign_scores/load_scores', [AssignScoreController::class, 'loadScores'])->name('assign_scores.load_scores');
    Route::resource('assign_scores', AssignScoreController::class);

    // key Parameters
    Route::resource('programmes', ProgrammeController::class);
    Route::resource('score_cards', ScoreCardController::class);

    // 
    Route::post('verify_teams', [TeamController::class, 'verifyTeams'])->name('verify_teams');
    Route::post('verification_teams', [TeamController::class, 'verificationTeams'])->name('verification_teams');
    Route::resource('teams', TeamController::class);

    // User Profiles
    Route::post('user_profiles/delete_profile_pic/{user}', [UserProfileController::class, 'delete_profile_pic'])->name('user_profiles.delete_profile_pic');
    Route::post('user_profiles/update_active_profile/{user}', [UserProfileController::class, 'update_active_profile'])->name('user_profiles.update_active_profile');
    Route::get('user_profiles/active_profile', [UserProfileController::class, 'active_profile'])->name('user_profiles.active_profile');
    Route::resource('user_profiles', UserProfileController::class);

    // View Reports
    Route::get('reports/team/summary_performance', [ReportController::class, 'teamPerformanceSummary'])->name('reports.team_summary_performance');
    Route::get('reports/team/size_summary', [ReportController::class, 'teamSizeSummary'])->name('reports.team_size_summary');
    Route::get('reports/team/metric_summary', [ReportController::class, 'metricSummary'])->name('reports.metric_summary');
    Route::get('reports/monthly_pledge_vs_mission', [ReportController::class, 'monthlyPledgeVsMission'])->name('reports.monthly_pledge_vs_mission');
    Route::get('reports/team_report_card', [ReportController::class, 'teamReportCard'])->name('reports.team_report_card');
    Route::get('reports/monthly_pledge', [ReportController::class, 'monthlyPledge'])->name('reports.monthly_pledge');
    Route::get('reports/score_variance', [ReportController::class, 'scoreVariance'])->name('reports.score_variance');
    // 
    Route::post('reports/score_variance', [ReportController::class, 'scoreVariance'])->name('reports.score_variance.post');
    Route::post('reports/monthly_pledge', [ReportController::class, 'monthlyPledge'])->name('reports.monthly_pledge.post');
    Route::post('reports/team_report_card', [ReportController::class, 'teamReportCard'])->name('reports.team_report_card.post');
    Route::post('reports/monthly_pledge_vs_mission', [ReportController::class, 'monthlyPledgeVsMission'])->name('reports.monthly_pledge_vs_mission.post');
    Route::post('reports/team/metric_summary', [ReportController::class, 'metricSummary'])->name('reports.metric_summary.post');
    Route::post('reports/team/size_summary', [ReportController::class, 'teamSizeSummary'])->name('reports.team_size_summary.post');
    Route::post('reports/team/summary_performance', [ReportController::class, 'teamPerformanceSummary'])->name('reports.team_summary_performance.post');



    // PDF Report
    Route::get('pdfs/agenda/{agenda}/{token}', [PdfController::class, 'print_agenda'])->name('pdfs.print_agenda');

    // Storage
    Route::get('storage/{file_params}', [StorageController::class, 'file_render'])->name('storage.file_render');
    Route::get('storage/download/{file_params}', [StorageController::class, 'file_download'])->name('storage.file_download');

    // Configuration
    Route::post('general_settings', [ConfigController::class, 'generalSettings'])->name('config.general_settings.post');
    Route::get('general_settings', [ConfigController::class, 'generalSettings'])->name('config.general_settings');
    Route::get('clear-cache', [ConfigController::class, 'clearCache'])->name('config.clear_cache');
    Route::get('site-down', [ConfigController::class, 'siteDown'])->name('config.site_down');
});
