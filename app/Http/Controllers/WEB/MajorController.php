<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;

class MajorController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $majors = Major::where('created_by', $user->id)
            ->where('status', '!=', 'deleted')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->get();

        return view('/pages/major.list', ['majors' => $majors]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $major = Major::findOrFail($id);
            $major->status = 'deleted';
            $major->save();
            return redirect()->route('major.list')->with('success', 'Branş silindi.');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Branş bulunamadı');
        }
    }

    public function filter(Request $request)
    {
        $user = Auth::user();

        $name = $request->input('name');
        $status = $request->input('status');

        $query = Major::query();

        if (!empty($name)) {
            $query->where('name', 'like', "%$name%");
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }
        $query->where('status', '!=', 'deleted');
        $query->where('created_by', $user->id);
        $query->orderByDesc('updated_at');
        $query->orderByDesc('created_at');

        $majors = $query->get();
        return view('/pages/major.list', ['majors' => $majors,'name'=>$name, 'status'=>$status, 'filter'=>true]);
    }

    public function add()
    {
        $user = Auth::user();

        return view('/pages/major.add');
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        try {
            $major = Major::findOrFail($id);
            return view('/pages/major.edit',compact('major'));
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Branş bulunamadı');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        try {
            $major = Major::findOrFail($id);

            $major->name = $validatedData['name'];
            $major->status = $validatedData['status'];
            $major->save();
            return redirect()->back()->with('success', 'Branş başarıyla güncellendi');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Branş bulunamadı');
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
                'status' => 'required'
            ]);

            $major = new Major;
            $major->name = $validatedData['name'];
            $major->status = $validatedData['status'];
            $major->created_by = $user->id;
            $major->save();


            return redirect()->route('major.list')->with('success', 'Branş başarıyla eklendi.');
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
