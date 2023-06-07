<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;

class CampusController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $campuses = Campus::where('created_by', $user->id)
            ->where('status', '!=', 'deleted')
            ->get();

        return view('/pages/campus.list', ['campuses' => $campuses]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $campus = Campus::findOrFail($id);
            $campus->status = 'deleted';
            $campus->save();
            return redirect()->route('campus.list')->with('success', 'Kampüs silindi.');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Kampüs bulunamadı');
        }
    }

    public function filter(Request $request)
    {
        $user = Auth::user();

        $name = $request->input('name');
        $status = $request->input('status');

        $query = Campus::query();

        if (!empty($name)) {
            $query->where('name', 'like', "%$name%");
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }
        $query->where('status', '!=', 'deleted');
        $query->where('created_by', $user->id);

        $campuses = $query->get();
        return view('/pages/campus.list', ['campuses' => $campuses,'name'=>$name]);
    }

    public function add()
    {
        $user = Auth::user();

        return view('/pages/campus.add');
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        try {
            $campus = Campus::findOrFail($id);
            return view('/pages/campus.edit',compact('campus'));
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Kampüs bulunamadı');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        try {
            $campus = Campus::findOrFail($id);

            $campus->name = $validatedData['name'];
            $campus->status = $validatedData['status'];
            $campus->save();
            return redirect()->back()->with('success', 'Kampüs başarıyla güncellendi');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Kampüs bulunamadı');
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

            $campus = new Campus;
            $campus->name = $validatedData['name'];
            $campus->status = $validatedData['status'];
            $campus->created_by = $user->id;
            $campus->save();


            return redirect()->route('campus.list')->with('success', 'Kampüs başarıyla eklendi.');
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