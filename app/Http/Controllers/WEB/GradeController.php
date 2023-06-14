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
use Illuminate\Validation\ValidationException;

class GradeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $grades = Grade::where('created_by', $user->id)
            ->where('status', '!=', 'deleted')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->get();


        return view('/pages/grade.list', ["grades"=>$grades]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $branch = Grade::findOrFail($id);
            $branch->status = 'deleted';
            $branch->save();
            return redirect()->route('grade.list')->with('success', 'Silme işlemi başarıyla gerçekleşti');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Veri bulunamadı');
        }
    }

    public function filter(Request $request)
    {
        $user = Auth::user();

        $name = $request->input('name');
        $status = $request->input('status');

        $query = Grade::query();

        if (!empty($name)) {
            $query->where('name', 'like', "%$name%");
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }
        $query->where('status', '!=', 'deleted');
        $query->where('created_by', $user->id);


        $grades = $query->get();
        return view('/pages/grade.list',
            ['grades' => $grades,'name'=>$name,'status'=>$status,"filter"=>true]
        );
    }

    public function add()
    {
        $user = Auth::user();

        return view('/pages/grade.add');
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        try {
            $grade = Grade::findOrFail($id);

            return view('/pages/grade.edit', ["grade"=>$grade]);

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
                'name' => 'required',
                'status' => 'required'
            ]);


            $grade = Grade::findOrFail($id);

            $grade->name = $validatedData['name'];
            $grade->status = $validatedData['status'];
            $grade->save();
            return redirect()->back()->with('success', 'Bilgiler güncellendi');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Veri bulunamadı');
        }
        catch (ValidationException $exception) {
            return redirect()->back()->with('error', __('Zorunlu alanları doldurunuz'));
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
                'status' => 'required'
            ]);

            $grade = new Grade;
            $grade->name = $validatedData['name'];
            $grade->status = $validatedData['status'];
            $grade->created_by = $user->id;
            $grade->save();


            return redirect()->route('grade.list')->with('success', 'Ekleme işlemi başarılı');
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
