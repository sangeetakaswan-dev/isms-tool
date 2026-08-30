<?php

use App\Http\Controllers\AssessmentTeamController;
use Illuminate\Support\Facades\Route;

Route::prefix('assessments')->group(function () {
    Route::get('{assessment}/team', [AssessmentTeamController::class, 'index']);
    Route::post('{assessment}/team', [AssessmentTeamController::class, 'store']);
    Route::post('{assessment}/team/bulk', [AssessmentTeamController::class, 'bulkStore']);
    Route::put('{assessment}/team/{userId}', [AssessmentTeamController::class, 'update']);
    Route::delete('{assessment}/team/{userId}', [AssessmentTeamController::class, 'destroy']);
});