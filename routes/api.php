<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationsController;
use App\Http\Controllers\SpotsController;
use App\Http\Controllers\VaccinationController;
use App\Models\Consultations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix("v1")->group(function () {
    Route::post("login", [AuthController::class, "Login"]);
    Route::post("logout", [AuthController::class, "Logout"]);

    Route::middleware("auth.token")->group(function () {
        Route::post("consultations", [ConsultationsController::class, "store"]);
        Route::get("consultations", [ConsultationsController::class, "show"]);
        Route::get("spots", [SpotsController::class, "index"]);
        Route::get("spots/{spots}", [SpotsController::class, "show"]);
        Route::post("vaccinations", [VaccinationController::class, "store"]);
        Route::get("vaccinations", [VaccinationController::class, "show"]);
    });
});
