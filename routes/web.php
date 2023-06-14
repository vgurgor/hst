<?php

use App\Http\Controllers\LangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WEB\CampusController;
use App\Http\Controllers\WEB\BranchController;
use App\Http\Controllers\WEB\ClassroomController;
use App\Http\Controllers\WEB\GradeController;
use App\Http\Controllers\WEB\IndexController;
use App\Http\Controllers\WEB\LessonController;
use App\Http\Controllers\WEB\LessonSlotController;
use App\Http\Controllers\WEB\MajorController;
use App\Http\Controllers\WEB\TeacherController;
use App\Http\Controllers\WEB\TimetableCreatorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Route::get('/dashboard', [IndexController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/timetables/class/{id}', [IndexController::class, 'classtimetable'])->middleware(['auth', 'verified'])->name('timetable.class');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::get('/campus', [CampusController::class, 'index'])->middleware(['auth', 'verified'])->name('campus.list');
Route::put('/campus/delete/{id}', [CampusController::class, 'delete'])->middleware(['auth', 'verified'])->name('campus.delete');
Route::get('/campus/edit/{id}', [CampusController::class, 'edit'])->middleware(['auth', 'verified'])->name('campus.edit');
Route::put('/campus/{id}', [CampusController::class, 'update'])->middleware(['auth', 'verified'])->name('campus.update');
Route::post('/campus/filter', [CampusController::class, 'filter'])->middleware(['auth', 'verified'])->name('campus.filter');
Route::get('/campus/add', [CampusController::class, 'add'])->middleware(['auth', 'verified'])->name('campus.add');
Route::post('/campus', [CampusController::class, 'store'])->middleware(['auth', 'verified'])->name('campus.store');

Route::get('/branch', [BranchController::class, 'index'])->middleware(['auth', 'verified'])->name('branch.list');
Route::put('/branch/delete/{id}', [BranchController::class, 'delete'])->middleware(['auth', 'verified'])->name('branch.delete');
Route::get('/branch/edit/{id}', [BranchController::class, 'edit'])->middleware(['auth', 'verified'])->name('branch.edit');
Route::put('/branch/{id}', [BranchController::class, 'update'])->middleware(['auth', 'verified'])->name('branch.update');
Route::post('/branch/filter', [BranchController::class, 'filter'])->middleware(['auth', 'verified'])->name('branch.filter');
Route::get('/branch/add', [BranchController::class, 'add'])->middleware(['auth', 'verified'])->name('branch.add');
Route::post('/branch', [BranchController::class, 'store'])->middleware(['auth', 'verified'])->name('branch.store');

Route::get('/grade', [GradeController::class, 'index'])->middleware(['auth', 'verified'])->name('grade.list');
Route::put('/grade/delete/{id}', [GradeController::class, 'delete'])->middleware(['auth', 'verified'])->name('grade.delete');
Route::get('/grade/edit/{id}', [GradeController::class, 'edit'])->middleware(['auth', 'verified'])->name('grade.edit');
Route::put('/grade/{id}', [GradeController::class, 'update'])->middleware(['auth', 'verified'])->name('grade.update');
Route::post('/grade/filter', [GradeController::class, 'filter'])->middleware(['auth', 'verified'])->name('grade.filter');
Route::get('/grade/add', [GradeController::class, 'add'])->middleware(['auth', 'verified'])->name('grade.add');
Route::post('/grade', [GradeController::class, 'store'])->middleware(['auth', 'verified'])->name('grade.store');

Route::get('/classroom', [ClassroomController::class, 'index'])->middleware(['auth', 'verified'])->name('classroom.list');
Route::put('/classroom/delete/{id}', [ClassroomController::class, 'delete'])->middleware(['auth', 'verified'])->name('classroom.delete');
Route::get('/classroom/edit/{id}', [ClassroomController::class, 'edit'])->middleware(['auth', 'verified'])->name('classroom.edit');
Route::put('/classroom/{id}', [ClassroomController::class, 'update'])->middleware(['auth', 'verified'])->name('classroom.update');
Route::post('/classroom/filter', [ClassroomController::class, 'filter'])->middleware(['auth', 'verified'])->name('classroom.filter');
Route::get('/classroom/add', [ClassroomController::class, 'add'])->middleware(['auth', 'verified'])->name('classroom.add');
Route::post('/classroom', [ClassroomController::class, 'store'])->middleware(['auth', 'verified'])->name('classroom.store');

Route::get('/major', [MajorController::class, 'index'])->middleware(['auth', 'verified'])->name('major.list');
Route::put('/major/delete/{id}', [MajorController::class, 'delete'])->middleware(['auth', 'verified'])->name('major.delete');
Route::get('/major/edit/{id}', [MajorController::class, 'edit'])->middleware(['auth', 'verified'])->name('major.edit');
Route::put('/major/{id}', [MajorController::class, 'update'])->middleware(['auth', 'verified'])->name('major.update');
Route::post('/major/filter', [MajorController::class, 'filter'])->middleware(['auth', 'verified'])->name('major.filter');
Route::get('/major/add', [MajorController::class, 'add'])->middleware(['auth', 'verified'])->name('major.add');
Route::post('/major', [MajorController::class, 'store'])->middleware(['auth', 'verified'])->name('major.store');

Route::get('/teacher', [TeacherController::class, 'index'])->middleware(['auth', 'verified'])->name('teacher.list');
Route::put('/teacher/delete/{id}', [TeacherController::class, 'delete'])->middleware(['auth', 'verified'])->name('teacher.delete');
Route::get('/teacher/edit/{id}', [TeacherController::class, 'edit'])->middleware(['auth', 'verified'])->name('teacher.edit');
Route::put('/teacher/{id}', [TeacherController::class, 'update'])->middleware(['auth', 'verified'])->name('teacher.update');
Route::post('/teacher/filter', [TeacherController::class, 'filter'])->middleware(['auth', 'verified'])->name('teacher.filter');
Route::get('/teacher/add', [TeacherController::class, 'add'])->middleware(['auth', 'verified'])->name('teacher.add');
Route::post('/teacher', [TeacherController::class, 'store'])->middleware(['auth', 'verified'])->name('teacher.store');

Route::get('/lesson', [LessonController::class, 'index'])->middleware(['auth', 'verified'])->name('lesson.list');
Route::put('/lesson/delete/{id}', [LessonController::class, 'delete'])->middleware(['auth', 'verified'])->name('lesson.delete');
Route::get('/lesson/edit/{id}', [LessonController::class, 'edit'])->middleware(['auth', 'verified'])->name('lesson.edit');
Route::put('/lesson/{id}', [LessonController::class, 'update'])->middleware(['auth', 'verified'])->name('lesson.update');
Route::post('/lesson/filter', [LessonController::class, 'filter'])->middleware(['auth', 'verified'])->name('lesson.filter');
Route::get('/lesson/add', [LessonController::class, 'add'])->middleware(['auth', 'verified'])->name('lesson.add');
Route::post('/lesson', [LessonController::class, 'store'])->middleware(['auth', 'verified'])->name('lesson.store');

Route::get('/lesson-slot', [LessonSlotController::class, 'index'])->middleware(['auth', 'verified'])->name('lesson-slot.list');
Route::put('/lesson-slot/delete/{id}', [LessonSlotController::class, 'delete'])->middleware(['auth', 'verified'])->name('lesson-slot.delete');
Route::post('/lesson-slot/filter', [LessonSlotController::class, 'filter'])->middleware(['auth', 'verified'])->name('lesson-slot.filter');
Route::get('/lesson-slot/add', [LessonSlotController::class, 'add'])->middleware(['auth', 'verified'])->name('lesson-slot.add');
Route::post('/lesson-slot', [LessonSlotController::class, 'store'])->middleware(['auth', 'verified'])->name('lesson-slot.store');


Route::get('/timetablecreator/wizard', [TimetableCreatorController::class, 'wizard'])->middleware(['auth', 'verified'])->name('timetablecreator.wizard');
Route::post('/timetablecreator/ajaxbranches', [TimetableCreatorController::class, 'ajaxbranches'])->middleware(['auth', 'verified'])->name('timetablecreator.ajaxbranches');
Route::post('/timetablecreator/ajaxgrades', [TimetableCreatorController::class, 'ajaxgrades'])->middleware(['auth', 'verified'])->name('timetablecreator.ajaxgrades');
Route::post('/timetablecreator/ajaxclassrooms', [TimetableCreatorController::class, 'ajaxclassrooms'])->middleware(['auth', 'verified'])->name('timetablecreator.ajaxclassrooms');
Route::post('/timetablecreator/ajaxcheckstep', [TimetableCreatorController::class, 'ajaxcheckstep'])->middleware(['auth', 'verified'])->name('timetablecreator.ajaxcheckstep');
Route::post('/timetablecreator/ajaxdownloadoptimizationdataset', [TimetableCreatorController::class, 'ajaxdownloadoptimizationdataset'])->middleware(['auth', 'verified'])->name('timetablecreator.ajaxdownloadoptimizationdataset');
Route::post('/timetablecreator/uploadoutputfile', [TimetableCreatorController::class, 'uploadoutputfile'])->middleware(['auth', 'verified'])->name('timetablecreator.uploadoutputfile');

Route::get('lang/home', [LangController::class, 'index']);
Route::get('lang/change/{lang}', [LangController::class, 'change'])->name('changeLang');

require __DIR__.'/auth.php';
