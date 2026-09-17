<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - The Phantom Prototype (Noir Detective Game)
|--------------------------------------------------------------------------
*/

// Main Detective Hubs
Route::get('/', [GameController::class, 'welcome'])->name('game.welcome');
Route::get('/desk/{caseId?}', [GameController::class, 'desk'])->name('game.desk');
Route::get('/board/{caseId?}', [GameController::class, 'board'])->name('game.board');
Route::get('/interrogation/{caseId?}/{suspectId?}', [GameController::class, 'interrogation'])->name('game.interrogation');

// Game Engine API Endpoints
Route::prefix('api/game')->name('api.game.')->group(function () {
    Route::get('/cases/{caseId}/dialogues/{suspectId?}', [GameController::class, 'apiGetDialogues'])->name('dialogues');
    Route::post('/present-evidence', [GameController::class, 'apiPresentEvidence'])->name('present_evidence');
    Route::post('/accuse', [GameController::class, 'apiEvaluateAccusation'])->name('accuse');
    Route::post('/save', [GameController::class, 'apiSaveGame'])->name('save');
    Route::get('/load/{slot}', [GameController::class, 'apiLoadGame'])->name('load');
    Route::get('/saves', [GameController::class, 'apiListSaves'])->name('saves');
    Route::post('/reset/{caseId?}', [GameController::class, 'apiResetGame'])->name('reset');
});
