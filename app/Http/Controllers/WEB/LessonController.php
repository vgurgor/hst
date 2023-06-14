<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Major;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LessonController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $lessons = Lesson::with('major','grade')
            ->where('created_by', $user->id)
            ->where('status', '!=', 'deleted')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->get();

        $majors = Major::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();


        if ($majors->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle branş oluşturmalısınız');
        }

        $grades = Grade::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($grades->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle düzey oluşturmalısınız');
        }

        return view('/pages/lesson.list', ['lessons' => $lessons,"majors"=>$majors, "grades"=>$grades]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $lesson = Lesson::findOrFail($id);
            $lesson->status = 'deleted';
            $lesson->save();
            return redirect()->route('lesson.list')->with('success', 'Silme işlemi başarıyla gerçekleşti');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Veri bulunamadı');
        }
    }

    public function filter(Request $request)
    {
        $user = Auth::user();

        $name = $request->input('name');
        $major_id = $request->input('major_id');
        $grade_id = $request->input('grade_id');
        $weekly_frequency = $request->input('weekly_frequency');
        $status = $request->input('status');

        $query = Lesson::query();

        if (!empty($name)) {
            $query->where('name', 'like', "%$name%");
        }

        if (!empty($major_id)) {
            $query->whereIn('major_id',  $major_id);
        }

        if (!empty($grade_id)) {
            $query->whereIn('grade_id', $grade_id);
        }

        if (!empty($weekly_frequency)) {
            $query->where('weekly_frequency', $weekly_frequency);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }
        $query->where('status', '!=', 'deleted');
        $query->where('created_by', $user->id);
        $query->orderByDesc('updated_at');
        $query->orderByDesc('created_at');

        $lessons = $query->get();

        $majors = Major::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();


        if ($majors->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle branş oluşturmalısınız');
        }

        $grades = Grade::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($grades->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle düzey oluşturmalısınız');
        }

        return view('/pages/lesson.list', ['lessons' => $lessons, 'major_id'=> $major_id, 'grade_id'=> $grade_id, 'weekly_frequency'=> $weekly_frequency, 'name'=>$name, 'status'=>$status, "majors"=>$majors, "grades"=>$grades, 'filter'=>true]);
    }

    public function add()
    {
        $user = Auth::user();

        $majors = Major::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();


        if ($majors->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle branş oluşturmalısınız');
        }

        $grades = Grade::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($grades->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle düzey oluşturmalısınız');
        }

        return view('/pages/lesson.add',  ["majors"=>$majors, "grades"=>$grades]);
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        try {
            $lesson = Lesson::findOrFail($id);

            $majors = Major::where('created_by', $user->id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();


            if ($majors->isEmpty()) {
                return redirect()->back()->with('error', 'Öncelikle branş oluşturmalısınız');
            }

            $grades = Grade::where('created_by', $user->id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            if ($grades->isEmpty()) {
                return redirect()->back()->with('error', 'Öncelikle düzey oluşturmalısınız');
            }

            return view('/pages/lesson.edit',['lesson'=>$lesson, "majors"=>$majors, "grades"=>$grades]);
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Veri bulunamadı');
        }
    }
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'major_id' => 'required',
                'grade_id' => 'required',
                'name' => 'required',
                'weekly_frequency' => 'required',
                'status' => 'required'
            ]);

            $lesson = Lesson::findOrFail($id);

            $lesson->major_id           = $validatedData['major_id'];
            $lesson->grade_id           = $validatedData['grade_id'];
            $lesson->name               = $validatedData['name'];
            $lesson->weekly_frequency   = $validatedData['weekly_frequency'];
            $lesson->status             = $validatedData['status'];
            $lesson->save();
            return redirect()->back()->with('success', 'Bilgiler güncellendi');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Veri bulunamadı');
        }
        catch (ValidationException $exception) {
            return redirect()->back()->with('error', __('Zorunlu alanları doldurunuz'));
        }catch (\Exception $exception){
            return redirect()->back()->with('error', __( ($exception->getMessage() ? $exception->getMessage() : "Hatalı istek")));
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request)
    {
        $user = Auth::user();

        try {
            $validatedData = $request->validate([
                'major_id' => 'required',
                'grade_id' => 'required',
                'name' => 'required',
                'weekly_frequency' => 'required',
                'status' => 'required'
            ]);

            $lesson                     = new Lesson;
            $lesson->major_id           = $validatedData['major_id'];
            $lesson->grade_id           = $validatedData['grade_id'];
            $lesson->name               = $validatedData['name'];
            $lesson->weekly_frequency   = $validatedData['weekly_frequency'];
            $lesson->status             = $validatedData['status'];
            $lesson->created_by         = $user->id;
            $lesson->save();

            return redirect()->route('lesson.list')->with('success', 'Ekleme işlemi başarılı');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Kaydetme sırasında hata oluştu');
        }
        catch (ValidationException $exception) {
            return redirect()->back()->with('error', 'Zorunlu alanları doldurunuz');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', __( ($exception->getMessage() ? $exception->getMessage() : "Hatalı istek")));
        }
    }
}
