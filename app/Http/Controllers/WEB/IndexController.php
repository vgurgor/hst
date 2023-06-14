<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\LessonSlot;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $timetablesData = Timetable::with('classroom.grade','classroom.branch')
            ->where('created_by', $user->id)
            ->where('status', 'active')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->get();

        $timetables = array();
        foreach ($timetablesData as $timetablesValue){
            $timetables[$timetablesValue->classroom_id] = array(
                "classroom_id"=>$timetablesValue->classroom->id,
                "campus_name"=>$timetablesValue->classroom->name,
                "branch_name"=>$timetablesValue->classroom->branch->name,
                "grade_name"=>$timetablesValue->classroom->grade->name,
                "classroom_name"=>$timetablesValue->classroom->name,
            );
        }

        $teacher_timetablesData = Timetable::with('teacher.branches','teacher.majors')
            ->where('created_by', $user->id)
            ->where('status', 'active')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->get();

        $teacher_timetables = array();
        foreach ($teacher_timetablesData as $teacher_timetablesValue){
            $teacher_timetables[$teacher_timetablesValue->teacher->id] = $teacher_timetablesValue->teacher;
        }

        return view('dashboard', ['timetables' => $timetables, 'teacher_timetables'=>$teacher_timetables]);
    }

    public function classtimetable(Request $request, $id){
        $user = Auth::user();

        $timetable = Timetable::join('lesson_slots', 'timetables.lesson_slot_id', '=', 'lesson_slots.id')
            ->with('classroom.campus', 'classroom.grade', 'classroom.branch', 'lesson.major', 'teacher.majors', 'lessonslot')
            ->where('timetables.created_by', $user->id)
            ->where('timetables.status', 'active')
            ->where('timetables.classroom_id', $id)
            ->orderBy('lesson_slots.day')
            ->orderBy('lesson_slots.start_time')
            ->get();

        $generalInfo = array();
        foreach ($timetable as $value){
            $generalInfo[$value->classroom->id]["campus_name"] = $value->classroom->campus->name;
            $generalInfo[$value->classroom->id]["branch_name"] = $value->classroom->branch->name;
            $generalInfo[$value->classroom->id]["classroom_name"] = $value->classroom->name;
        }

        $generalInfo = $generalInfo[$id];


        $lessons = array();
        foreach ($timetable as $value){
            $lessons[$value->lesson->id]["name"] = $value->lesson->name;
            $lessons[$value->lesson->id]["major_name"] = $value->lesson->major->name;
            $lessons[$value->lesson->id]["teacher"] = "";
            if (in_array($value->lesson->major_id, $value->teacher->majors->pluck('id')->toArray())) {
                $lessons[$value->lesson->id]["teacher"] = $value->teacher->first_name." ". $value->teacher->last_name;
            }
            $lessons[$value->lesson->id]["weekly_frequency"] = $value->lesson->weekly_frequency;
        }

        $lessonhours = array();
        foreach ($timetable as $value){
            $lessonhours[$value->lessonslot->start_time."-".$value->lessonslot->end_time] = array(
                "start"=>$value->lessonslot->start_time,
                "end"=>$value->lessonslot->end_time,
            );
        }

        $days = array(
            "1" => "Pazartesi",
            "2" => "Salı",
            "3" => "Çarşamba",
            "4" => "Perşembe",
            "5" => "Cuma",
            "6" => "Cumartesi",
            "7" => "Pazar"
        );


        $lessonsTable = array();
        foreach ($timetable as $value){
            $lessonsTable[$value->lessonslot->day][] = $value->lesson->major->name;
        }

        return view('/pages/timetablecreator.timetable_class', ['timetable'=>$timetable, 'generalInfo'=>$generalInfo, 'lessons'=>$lessons, 'lessonhours'=>$lessonhours, 'lessonsTable'=>$lessonsTable]);
    }

}
