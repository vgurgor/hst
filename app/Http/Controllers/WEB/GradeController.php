<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchType;
use App\Models\Campus;
use App\Models\Grade;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;

class GradeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $grades = Grade::with('branch')
            ->with('campus')
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


        return view('/pages/grade.list', ["grades"=>$grades,"branchTypes" => $branchTypesData,'branches' => $branches]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $branch = Grade::findOrFail($id);
            $branch->status = 'deleted';
            $branch->save();
            return redirect()->route('grade.list')->with('success', 'Düzey silindi.');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Düzey bulunamadı');
        }
    }

    public function filter(Request $request)
    {
        $user = Auth::user();

        $branch_id = $request->input('branch_id');
        $name = $request->input('name');
        $status = $request->input('status');

        $query = Grade::query();

        if (!empty($branch_id)) {
            $query->where('branch_id', $branch_id);
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

        $branchTypes = BranchType::get();
        $branchTypesData = array();
        foreach ($branchTypes as $branchType){
            $branchTypesData[$branchType->code] = $branchType->name;
        }

        $grades = $query->get();
        return view('/pages/grade.list',
            ['grades' => $grades,'branch_id'=>$branch_id,'name'=>$name,'status'=>$status,"branches"=>$branches,"branchTypes" => $branchTypesData,"filter"=>true]
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

        return view('/pages/grade.add', ["branches" => $branches]);
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        try {
            $grade = Grade::findOrFail($id);
            $branches = Branch::where('created_by', $user->id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            if ($branches->isEmpty()) {
                return redirect()->back()->with('error', 'Öncelikle şube oluşturmalısınız');
            }


            return view('/pages/grade.edit', ["grade"=>$grade,"branches" => $branches]);

        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Düzey bulunamadı');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'branch_id' => 'required',
            'name' => 'required',
            'status' => 'required'
        ]);

        try {
            $grade = Grade::findOrFail($id);

            $grade->name = $validatedData['name'];
            $grade->branch_id = $validatedData['branch_id'];
            $grade->status = $validatedData['status'];
            $grade->save();
            return redirect()->back()->with('success', 'Düzey başarıyla güncellendi');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Düzey bulunamadı');
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
                'branch_id' => 'required'
            ]);

            $grade = new Grade;
            $grade->name = $validatedData['name'];
            $grade->status = $validatedData['status'];
            $grade->branch_id = $validatedData['branch_id'];
            $grade->created_by = $user->id;
            $grade->save();


            return redirect()->route('grade.list')->with('success', 'Düzey başarıyla eklendi.');
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
