<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return response()->json($teachers);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'status' => 'required',
                'majors' => 'required'
            ]);

            $teacher = Teacher::create($validatedData);

            return response()->json(['message' => 'Öğretmen başarıyla oluşturuldu', 'data' => $teacher]);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);

            return response()->json($teacher);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Öğretmen bulunamadı'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            try {
                $validatedData = $request->validate([
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'status' => 'required',
                    'majors' => 'required'
                ]);

                $teacher->update($validatedData);
                return response()->json(['message' => 'Öğretmen bilgileri başarıyla güncellendi', 'data' => $teacher]);
            } catch (ValidationException $exception) {
                return response()->json(['errors' => $exception->errors()], 422);
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Öğretmen bulunamadı'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->delete();
            return response()->json(['message' => 'Öğretmen başarıyla silindi'], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Öğretmen bulunamadı'], 404);
        }
    }
}
