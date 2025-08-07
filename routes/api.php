<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\QuestionFormulaireController;
use App\Http\Controllers\PostuleOffreController;
use App\Http\Controllers\EnqueteurController;
use App\Http\Controllers\ReponseFormulaireController;


Route::put('/offers/{offer}', [OffreController::class, 'update'])->name('offers.update');
Route::get('/getAllOffers', [OffreController::class,'getAllOffers'])->name('getAllOffers');
Route::get('/offers/{offre}', [OffreController::class, 'getOffer'])->name('offers.show');
Route::apiResource('offres', OffreController::class);
Route::delete('/offers/{offre}', [OffreController::class, 'destroy'])->name('offers.destroy');


Route::get('/enqueteurs/view', [EnqueteurController::class, 'index'])->name('enqueteurs.index');


Route::apiResource('offres.questions', QuestionFormulaireController::class)
         ->shallow();


Route::apiResource('offres.postules', PostuleOffreController::class)
         ->shallow();


Route::apiResource('postules.reponses', ReponseFormulaireController::class)
         ->shallow();
