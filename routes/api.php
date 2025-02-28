<?php

use App\Http\Controllers\recetasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/recetas", [recetasController::class, 'index']);

Route::get("/recetas/{identificador}", [recetasController::class, 'show']);

Route::post("/recetas", [recetasController::class, 'store']);

Route::put("/recetas/{identificador}/editar", [recetasController::class, 'update']);

Route::delete("/recetas/{identificador}", [recetasController::class, 'destroy']);

Route::post('/recetas/subir-imagen', [RecetasController::class, 'subirImagen']);

Route::get("/recetas/tipo/{tipoReceta}", [recetasController::class, 'buscarPorTipo']);