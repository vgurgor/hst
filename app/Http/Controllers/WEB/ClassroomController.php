<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchType;
use App\Models\Classroom;
use App\Models\Grade;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;

class ClassroomController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $classrooms = Classroom::with('grade')
            ->with('branch')
            ->where('created_by', $user->id)
            ->where('status', '!=', 'deleted')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->get();


        $branchTypes = BranchType::get();

        $branchTypesData = array();
        foreach ($branchTypes as $branchType){
            $branchTypesData[$branchType->code] = $branchType->name;
        }

        $branches = Branch::with('campus')
            ->where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $branchesData = array();
        foreach ($branches as $branch){
            $branchesData[$branch->campus->name][$branch->id] = $branch->name;
        }

        $grades = Grade::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('/pages/classroom.list', ["classrooms"=>$classrooms,"branchTypes" => $branchTypesData,'branches' => $branchesData, 'grades'=>$grades]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $classroom = Classroom::findOrFail($id);
            $classroom->status = 'deleted';
            $classroom->save();
            return redirect()->route('classroom.list')->with('success', 'Düzey silindi.');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Sınıf bulunamadı');
        }
    }

    public function filter(Request $request)
    {
        $user = Auth::user();

        $branch_id  = $request->input('branch_id');
        $grade_id   = $request->input('grade_id');
        $name       = $request->input('name');
        $status     = $request->input('status');

        $query = Classroom::query();

        if (!empty($branch_id)) {
            $query->where('branch_id', $branch_id);
        }

        if (!empty($grade_id)) {
            $query->where('grade_id', $grade_id);
        }

        if (!empty($name)) {
            $query->where('name', 'like', "%$name%");
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }
        $query->where('status', '!=', 'deleted');
        $query->where('created_by', $user->id);

        $branches = Branch::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $branchesData = array();
        foreach ($branches as $branch){
            $branchesData[$branch->campus->name][$branch->id] = $branch->name;
        }

        $branchTypes = BranchType::get();
        $branchTypesData = array();
        foreach ($branchTypes as $branchType){
            $branchTypesData[$branchType->code] = $branchType->name;
        }

        $grades = Grade::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();


        $classrooms = $query->get();
        return view('/pages/classroom.list',
            ["classrooms"=>$classrooms,'grades'=>$grades,'branch_id'=>$branch_id,'grade_id'=>$grade_id,'name'=>$name,'status'=>$status,"branches"=>$branchesData,"branchTypes" => $branchTypesData,"filter"=>true]
        );
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

        $grades = Grade::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($grades->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle düzey oluşturmalısınız');
        }

        return view('/pages/classroom.add', ["branches" => $branchesData, "grades" => $grades]);
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        try {
            $classroom = Classroom::findOrFail($id);
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

            $grades = Grade::where('created_by', $user->id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            if ($grades->isEmpty()) {
                return redirect()->back()->with('error', 'Öncelikle düzey oluşturmalısınız');
            }


            return view('/pages/classroom.edit', ["classroom"=>$classroom,"branches" => $branchesData,"grades"=>$grades]);

        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Sınıf bulunamadı');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'branch_id' => 'required',
            'grade_id' => 'required',
            'name' => 'required',
            'status' => 'required'
        ]);

        try {
            $classroom          = Classroom::findOrFail($id);

            $classroom->name        = $validatedData['name'];
            $classroom->branch_id   = $validatedData['branch_id'];
            $classroom->grade_id    = $validatedData['grade_id'];
            $classroom->status      = $validatedData['status'];
            $classroom->save();
            return redirect()->back()->with('success', 'Sınıf başarıyla güncellendi');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Sınıf bulunamadı');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', ($exception->getMessage() ? $exception->getMessage() : "Hatalı istek"));
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
                'name' => 'required',
                'status' => 'required',
                'branch_id' => 'required',
                'grade_id' => 'required'
            ]);


            $classroom              = new Classroom();
            $classroom->name        = $validatedData['name'];
            $classroom->status      = $validatedData['status'];
            $classroom->branch_id   = $validatedData['branch_id'];
            $classroom->grade_id    = $validatedData['grade_id'];
            $classroom->created_by  = $user->id;
            $classroom->save();


            return redirect()->route('classroom.list')->with('success', 'Sınıf başarıyla eklendi.');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Kaydetme sırasında hata oluştu');
        }
        catch (ValidationException $exception) {
            return redirect()->back()->with('error', 'Zorunlu alanları doldurunuz');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', ($exception->getMessage() ? $exception->getMessage() : "Hatalı istek"));
        }
    }
}
