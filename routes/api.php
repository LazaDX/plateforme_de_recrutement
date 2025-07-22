<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\QuestionFormulaireController;
use App\Http\Controllers\PostuleOffreController;
use App\Http\Controllers\ReponseFormulaireController;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->group(function () {

// });

Route::apiResource('offres', OffreController::class);

Route::apiResource('offres.questions', QuestionFormulaireController::class)
         ->shallow(); // pour ne pas avoir toujours /offres/{offre}/questions/{id}


Route::apiResource('offres.postules', PostuleOffreController::class)
         ->shallow();


Route::apiResource('postules.reponses', ReponseFormulaireController::class)
         ->shallow();
