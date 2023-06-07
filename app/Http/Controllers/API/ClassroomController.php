<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::all();
        return response()->json($classrooms);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'grade_id' => 'required|exists:grades,id',
                'branch_id' => 'required|exists:branches,id',
                'status' => 'required'
            ]);

            $validatedData['created_by'] = Auth::id();

            $classroom = Classroom::create($validatedData);

            return response()->json(['message' => 'Sınıf başarıyla oluşturuldu', 'data' => $classroom]);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            $classroom = Classroom::findOrFail($id);

            return response()->json($classroom);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Sınıf bulunamadı'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $classroom = Classroom::findOrFail($id);
            try {
                $validatedData = $request->validate([
                    'name' => 'required',
                    'grade_id' => 'required|exists:grades,id',
                    'branch_id' => 'required|exists:branches,id',
                    'status' => 'required'
                ]);

                $validatedData['updated_by'] = Auth::id();

                $classroom->update($validatedData);
                return response()->json(['message' => 'Sınıf bilgileri başarıyla güncellendi', 'data' => $classroom]);
            } catch (ValidationException $exception) {
                return response()->json(['errors' => $exception->errors()], 422);
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Sınıf bulunamadı'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $classroom = Classroom::findOrFail($id);
            $classroom->delete();
            return response()->json(['message' => 'Sınıf başarıyla silindi'], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Sınıf bulunamadı'], 404);
        }
    }
}
