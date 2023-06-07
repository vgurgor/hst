<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::all();
        return response()->json($grades);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'branch_id' => 'required|exists:branches,id'
            ]);

            $validatedData['created_by'] = Auth::id();

            $grade = Grade::create($validatedData);

            return response()->json(['message' => 'Düzey başarıyla oluşturuldu', 'data' => $grade]);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            $grade = Grade::findOrFail($id);

            return response()->json($grade);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Düzey bulunamadı'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $grade = Grade::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required',
                'branch_id' => 'required|exists:branches,id'
            ]);

            $validatedData['updated_by'] = Auth::id();

            $grade->update($validatedData);

            return response()->json(['message' => 'Düzey bilgileri başarıyla güncellendi', 'data' => $grade]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Düzey bulunamadı'], 404);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $grade = Grade::findOrFail($id);
            $grade->delete();

            return response()->json(['message' => 'Düzey başarıyla silindi'], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Düzey bulunamadı'], 404);
        }
    }
}
