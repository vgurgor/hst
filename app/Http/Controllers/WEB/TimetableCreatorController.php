<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Campus;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\LessonSlot;
use App\Models\Major;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;

class TimetableCreatorController extends Controller
{
    public function wizard()
    {
        $user = Auth::user();

        $campuses = Campus::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($campuses->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle kampüs oluşturmalısınız');
        }

        return view('/pages/timetablecreator.wizard', ["campuses"=>$campuses]);
    }

    public function ajaxbranches(Request $request){

        $user = Auth::user();

        $campus_id  = $request->input('campus_id');

        if (!isset($campus_id) || $campus_id == null) {
            echo json_encode(array("status"=>"warning", "msg"=>"Kampüs seçilmelidir"));

            exit();
        }

        $branches = Branch::where('created_by', $user->id)
            ->where('campus_id', $campus_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($branches->isEmpty()) {
            echo json_encode(array("status"=>"warning", "msg"=>"Öncelikle şube oluşturmalısınız"));

            exit();
        }

        $branchesData = array();
        foreach ($branches as $branch){
            $branchesData[$branch->id] = $branch->name;
        }

        echo json_encode(array("data"=>$branchesData));
        exit();
    }

    public function ajaxgrades(Request $request){

        $user = Auth::user();

        $branch_id  = $request->input('branch_id');

        if (!isset($branch_id) || $branch_id == null || count($branch_id) == 0) {
            echo json_encode(array("status"=>"warning", "msg"=>"Devam etmek için en az 1 şube seçilmelidir"));
            exit();
        }

        $grades = Grade::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($grades->isEmpty()) {
            echo json_encode(array("status"=>"warning", "msg"=>"Öncelikle düzey oluşturmalısınız"));
            exit();
        }

        $gradesData = array();
        foreach ($grades as $grade){
            $gradesData[$grade->id] = $grade->name;
        }

        echo json_encode(array("data"=>$gradesData));
        exit();
    }

    public function ajaxclassrooms(Request $request){

        $user = Auth::user();

        $campus_id  = $request->input('campus_id');
        $branch_id  = $request->input('branch_id');
        $grade_id   = $request->input('grade_id');

        if (!isset($campus_id) || $campus_id == null) {
            echo json_encode(array("status"=>"warning", "msg"=>"Devam etmek için kampüs seçilmelidir"));
            exit();
        }

        if (!isset($branch_id) || $branch_id == null || count($branch_id) == 0) {
            echo json_encode(array("status"=>"warning", "msg"=>"Devam etmek için en az 1 şube seçilmelidir"));
            exit();
        }

        if (!isset($grade_id) || $grade_id == null || count($grade_id) == 0) {
            echo json_encode(array("status"=>"warning", "msg"=>"Devam etmek için en az 1 düzey seçilmelidir"));
            exit();
        }

        $classrooms = Classroom::where('created_by', $user->id)
            ->whereIn('branch_id', $branch_id)
            ->whereIn('grade_id', $grade_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($classrooms->isEmpty()) {
            echo json_encode(array("status"=>"warning", "msg"=>"Öncelikle sınıf oluşturmalısınız"));
            exit();
        }

        $classroomsData = array();
        foreach ($classrooms as $classroom){
            $classroomsData[$classroom->id] = $classroom->name;
        }

        echo json_encode(array("data"=>$classroomsData));
        exit();
    }
    public function ajaxcheckstep(Request $request){

        $user = Auth::user();

        $step       = $request->input('step');
        $campus_id  = $request->input('campus_id');
        $branch_id  = $request->input('branch_id');
        $grade_id   = $request->input('grade_id');
        $classrooms = $request->input('classrooms');

        if (!isset($step) || $step == null) {
            echo json_encode(array("status"=>"warning", "msg"=>"Hatalı istek"));
            exit();
        }

        $result = array();
        if($step == 1){
            if (!isset($campus_id) || $campus_id == null) {
                echo json_encode(array("status"=>"warning", "msg"=>"Devam etmek için kampüs seçilmelidir"));
                exit();
            }

            if (!isset($branch_id) || $branch_id == null || count($branch_id) == 0) {
                echo json_encode(array("status"=>"warning", "msg"=>"Devam etmek için en az 1 şube seçilmelidir"));
                exit();
            }

            if (!isset($grade_id) || $grade_id == null || count($grade_id) == 0) {
                echo json_encode(array("status"=>"warning", "msg"=>"Devam etmek için en az 1 düzey seçilmelidir"));
                exit();
            }

            if (!isset($classrooms) || $classrooms == null || count($classrooms) == 0) {
                echo json_encode(array("status"=>"warning", "msg"=>"Devam etmek için en az 1 sınıf seçilmelidir"));
                exit();
            }

            $classroomsGrades = array();
            $classroomsBranchIds = array();
            $classrooms = Classroom::whereIn('id',$classrooms)->get();
            foreach ($classrooms as $classroom){
                $classroomsGrades[$classroom->grade_id] = $classroom->grade_id;
                $classroomsBranchIds[$classroom->branch_id] = $classroom->branch_id;
            }

            $campusLessonSlots = LessonSlot::where('campus_id',$campus_id)
                ->where('created_by', $user->id)
                ->where('status', '!=', 'deleted')
                ->orderBy('campus_id')
                ->orderBy('day')
                ->orderBy('start_time')
                ->get();

            $totalDays  =count(array_unique(array_column($campusLessonSlots->toArray(), 'day')));
            $totalHours =$campusLessonSlots->count();

            $lessons = Lesson::whereIn('grade_id', array_keys($classroomsGrades))
                ->where('created_by', $user->id)
                ->where('status', '!=', 'deleted')
                ->orderByDesc('updated_at')
                ->orderByDesc('created_at')
                ->get();

            $lessonsArr = array();
            $lessonsMajors = array();
            foreach ($lessons as $lesson){
                $lessonsArr[$lesson->grade_id][] = $lesson;
                $lessonsMajors[$lesson->major_id] = $lesson->major_id;
            }

            $gradeTotalLessonHour = array();
            foreach ($lessonsArr as $key => $val){
                foreach ($val as $val2){
                    if(!in_array($key, array_keys($gradeTotalLessonHour))) { $gradeTotalLessonHour[$key] = 0;}
                    $gradeTotalLessonHour[$key] += $val2->weekly_frequency;
                }
            }

            $gradeResult = array();
            foreach (array_keys($classroomsGrades) as  $v){
                $gradeResult[$v] = ($gradeTotalLessonHour[$v] != $totalHours ? false : true);
            }

            $grades = Grade::whereIn('id', array_keys($classroomsGrades))
                ->where('created_by', $user->id)
                ->where('status', '!=', 'deleted')
                ->get();

            $gradeArr = array();
            foreach ($grades as $grade){
                $gradeArr[$grade->id] = $grade;
            }

            foreach ($gradeResult as $k=>$gResult){
                if(!$gResult){
                    $result["status"] = "error";
                    $result["errors"][] = $gradeArr[$k]->name." düzeyinde oluşturulan ders sayısı (".$gradeTotalLessonHour[$k].") ile kampüs haftalık ders slot sayısı (".$totalHours.")  uyuşmuyor!";
                }
            }
            if(array_key_exists("errors", $result)){
                $result["errors"] = implode("<br>",$result["errors"]);
            }

            $classroomsBranchIds    = array_keys($classroomsBranchIds);
            $lessonsMajors          = array_keys($lessonsMajors);

            $teachers = Teacher::with("majors")->whereHas('branches', function ($query) use ($classroomsBranchIds) {
                $query->whereIn('branch_id', $classroomsBranchIds);
            })
                ->whereHas('majors', function ($query) use ($lessonsMajors) {
                    $query->whereIn('major_id', $lessonsMajors);
                })
                ->get();

            $teachersData = array();
            foreach ($teachers as $teacher){
                foreach ($teacher->majors->pluck('name') as $tMajor){
                    $teachersData[$tMajor][$teacher->id] = $teacher->first_name." ".$teacher->last_name;
                }

            }

            $result["teachers"] = $teachersData;

        }
        echo json_encode($result);
        exit();
    }

}
