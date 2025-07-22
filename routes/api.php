<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Http\Controllers\OffresController;
use Http\Controllers\QuestionsFormulairesController;
use Http\Controllers\PostuleOffresController;
use Http\Controllers\ReponsesFormulairesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
 // CRUD Offres
    Route::apiResource('offres', OffresController::class);

    // CRUD Questions liées à une offre
    Route::apiResource('offres.questions', QuestionsFormulairesController::class)
         ->shallow(); // pour ne pas avoir toujours /offres/{offre}/questions/{id}

    // Postuler à une offre
    Route::apiResource('offres.postules', PostuleOffresController::class)
         ->shallow();

    // Réponses aux formulaires
    Route::apiResource('postules.reponses', ReponsesFormulairesController::class)
         ->shallow();
});
