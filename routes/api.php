<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\DepartamentoController;


Route::apiResource('funcionarios', FuncionarioController::class);
Route::apiResource('departamentos', DepartamentoController::class);

Route::get('/funcionarios-com-departamento', [FuncionarioController::class, 'indexWithDepartamento']);
Route::get('/departamentos-com-funcionarios', [DepartamentoController::class, 'indexWithFuncionarios']);
Route::get('/funcionario/{id}/departamento', [FuncionarioController::class, 'getDepartamento']);
Route::get('/departamento/{id}/funcionarios', [DepartamentoController::class, 'getFuncionarios']);
Route::post('/funcionarios', [FuncionarioController::class, 'store']);
Route::delete('/funcionarios/{id}', [FuncionarioController::class, 'destroy']);
Route::delete('/departamento/{id}', [DepartamentoController::class, 'destroy']);
Route::post('/departamento', [DepartamentoController::class, 'store']);