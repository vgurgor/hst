<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Major;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;

class TeacherController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $teachers = Teacher::with('branches','majors')
            ->where('created_by', $user->id)
            ->where('status', '!=', 'deleted')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->get();

        $branches = Branch::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($branches->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle şube oluşturmalısınız');
        }

        $branchesData = array();
        foreach ($branches as $branch){
            $branchesData[$branch->campus->name][$branch->id] = $branch->name;
        }

        $majors = Major::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();


        if ($branches->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle branş oluşturmalısınız');
        }


        return view('/pages/teacher.list', ['teachers'=>$teachers,"branches"=>$branchesData, "majors"=>$majors]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->status = 'deleted';
            $teacher->save();
            return redirect()->route('teacher.list')->with('success', 'Öğretmen silindi.');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Öğretmen bulunamadı');
        }
    }

    public function filter(Request $request)
    {
        $user = Auth::user();

        $branch_ids     = $request->input('branch_ids');
        $major_ids      = $request->input('major_ids');
        $first_name     = $request->input('first_name');
        $last_name      = $request->input('last_name');
        $status         = $request->input('status');

        $query = Teacher::query();

        if (!empty($branch_ids)) {
            $query->whereHas('branches', function ($query) use ($branch_ids) {
                $query->whereIn('branch_id', $branch_ids);
            });
        }

        if (!empty($major_ids)) {
            $query->whereHas('majors', function ($query) use ($major_ids) {
                $query->whereIn('major_id', $major_ids);
            });
        }

        if (!empty($first_name)) {
            $query->where('first_name', 'like', "%$first_name%");
        }

        if (!empty($last_name)) {
            $query->where('last_name', 'like', "%$last_name%");
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }
        $query->where('status', '!=', 'deleted');
        $query->where('created_by', $user->id);
        $query->orderByDesc('updated_at');
        $query->orderByDesc('created_at');

        $teachers = $query->get();

        $branches = Branch::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($branches->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle şube oluşturmalısınız');
        }

        $branchesData = array();
        foreach ($branches as $branch){
            $branchesData[$branch->campus->name][$branch->id] = $branch->name;
        }

        $majors = Major::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();


        if ($branches->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle branş oluşturmalısınız');
        }

        return view('/pages/teacher.list', ['teachers' => $teachers, 'branch_ids'=>$branch_ids, 'major_ids'=>$major_ids, 'first_name'=>$first_name, 'last_name'=>$last_name, 'status'=>$status, "branches"=>$branchesData, "majors"=>$majors, 'filter'=>true]);
    }

    public function add()
    {
        $user = Auth::user();

        $branches = Branch::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($branches->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle şube oluşturmalısınız');
        }

        $branchesData = array();
        foreach ($branches as $branch){
            $branchesData[$branch->campus->name][$branch->id] = $branch->name;
        }

        $majors = Major::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();


        if ($branches->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle branş oluşturmalısınız');
        }

        return view('/pages/teacher.add', ["branches"=>$branchesData, "majors"=>$majors]);
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        try {
            $teacher = Teacher::findOrFail($id);

            $branches = Branch::where('created_by', $user->id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            if ($branches->isEmpty()) {
                return redirect()->back()->with('error', 'Öncelikle şube oluşturmalısınız');
            }

            $branchesData = array();
            foreach ($branches as $branch){
                $branchesData[$branch->campus->name][$branch->id] = $branch->name;
            }

            $majors = Major::where('created_by', $user->id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            if ($branches->isEmpty()) {
                return redirect()->back()->with('error', 'Öncelikle branş oluşturmalısınız');
            }

            return view('/pages/teacher.edit', ['teacher'=>$teacher,"branches"=>$branchesData, "majors"=>$majors]);
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Öğretmen bulunamadı');
        }
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'major_ids' => 'required|array',
            'branch_ids' => 'required|array',
            'status' => 'required'
        ]);

        try {
            $teacher = Teacher::findOrFail($id);

            $teacher->first_name  = $validatedData['first_name'];
            $teacher->last_name   = $validatedData['last_name'];
            $teacher->status      = $validatedData['status'];
            $teacher->updated_By  = $user->id;
            $teacher->save();

            $teacher->branches()->sync($validatedData['branch_ids']);
            $teacher->majors()->sync($validatedData['major_ids']);

            return redirect()->back()->with('success', 'Öğretmen bilgileri başarıyla güncellendi');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Öğretmen bulunamadı');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', 'Hatalı istek '. $exception->getMessage());
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


            $validatedData = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'branch_ids' => 'required|array',
                'major_ids' => 'required|array',
                'status' => 'required'
            ]);


            $teacher = new Teacher;
            $teacher->first_name  = $validatedData['first_name'];
            $teacher->last_name   = $validatedData['last_name'];
            $teacher->status      = $validatedData['status'];
            $teacher->created_by  = $user->id;
            $teacher->save();

            $teacher->branches()->attach($validatedData['branch_ids']);

            $teacher->majors()->attach($validatedData['major_ids']);


            return redirect()->route('teacher.list')->with('success', 'Öğretmen başarıyla oluşturuldu.');

    }
}
