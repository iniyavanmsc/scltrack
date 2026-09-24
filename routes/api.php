<?php

use App\Http\Controllers\Api\ParentController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('parents', ParentController::class)->names('api.parents');
Route::apiResource('students', StudentController::class)->names('api.students');
