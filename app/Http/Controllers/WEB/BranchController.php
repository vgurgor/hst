<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchType;
use App\Models\Campus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BranchController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $branches = Branch::with('campus')
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

        $campuses = Campus::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();


        return view('/pages/branch.list', ['branches' => $branches,"branchTypes" => $branchTypesData,"campuses"=>$campuses]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $branch->status = 'deleted';
            $branch->save();
            return redirect()->route('branch.list')->with('success', 'Şube silindi.');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Şube bulunamadı');
        }
    }

    public function filter(Request $request)
    {
        $user = Auth::user();

        $campus_id = $request->input('campus_id');
        $name = $request->input('name');
        $status = $request->input('status');

        $query = Branch::query();

        if (!empty($campus_id)) {
            $query->whereIn('campus_id', $campus_id);
        }

        if (!empty($name)) {
            $query->where('name', 'like', "%$name%");
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }
        $query->where('status', '!=', 'deleted');
        $query->where('created_by', $user->id);

        $branchTypes = BranchType::get();

        $branchTypesData = array();
        foreach ($branchTypes as $branchType){
            $branchTypesData[$branchType->code] = $branchType->name;
        }

        $campuses = Campus::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();


        $branches = $query->get();
        return view('/pages/branch.list',
            ['branches' => $branches,'campus_id'=>$campus_id,'name'=>$name,'status'=>$status,"branchTypes" => $branchTypesData, "campuses"=>$campuses,"filter"=>true]
        );
    }

    public function add()
    {
        $user = Auth::user();

        $campuses = Campus::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($campuses->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle kampüs oluşturmalısınız');
        }

        $branchTypes = BranchType::get();

        return view('/pages/branch.add', ["campuses" => $campuses,"branchTypes" => $branchTypes]);
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        try {
            $branch = Branch::findOrFail($id);
            $campuses = Campus::where('created_by', $user->id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            if ($campuses->isEmpty()) {
                return redirect()->back()->with('error', 'Öncelikle kampüs oluşturmalısınız');
            }

            $branchTypes = BranchType::get();

            return view('/pages/branch.edit', ["branch"=>$branch,"campuses" => $campuses,"branchTypes" => $branchTypes]);

        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Şube bulunamadı');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        try {
            $branch = Branch::findOrFail($id);

            $branch->name = $validatedData['name'];
            $branch->status = $validatedData['status'];
            $branch->save();
            return redirect()->back()->with('success', 'Şube başarıyla güncellendi');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Şube bulunamadı');
        }
        catch (ValidationException $exception) {
            return redirect()->back()->with('error', __('Zorunlu alanları doldurunuz'));
        }catch (\Exception $exception){
            return redirect()->back()->with('error', 'Hatalı istek');
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
                'campus_id' => 'required',
                'type'=>'required'
            ]);

            $branch = new Branch;
            $branch->name = $validatedData['name'];
            $branch->status = $validatedData['status'];
            $branch->campus_id = $validatedData['campus_id'];
            $branch->type = $validatedData['type'];
            $branch->created_by = $user->id;
            $branch->save();


            return redirect()->route('branch.list')->with('success', 'Şube başarıyla eklendi.');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Kaydetme sırasında hata oluştu');
        }
        catch (ValidationException $exception) {
            return redirect()->back()->with('error', 'Zorunlu alanları doldurunuz');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', 'Hatalı istek');
        }
    }
}
