<?php

use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\CampusController;
use App\Http\Controllers\API\ClassroomController;
use App\Http\Controllers\API\GradeController;
use App\Http\Controllers\API\LessonController;
use App\Http\Controllers\API\LessonSlotController;
use App\Http\Controllers\API\MajorController;
use App\Http\Controllers\API\TeacherController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('campuses', CampusController::class);
    Route::apiResource('branches', BranchController::class);
    Route::apiResource('grades', GradeController::class);
    Route::apiResource('classrooms', ClassroomController::class);
    Route::apiResource('majors', MajorController::class);
    Route::apiResource('teachers', TeacherController::class);
    Route::apiResource('lessons', LessonController::class);
    Route::apiResource('lesson-slots', LessonSlotController::class);
});


Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token]);
    } else {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
});
