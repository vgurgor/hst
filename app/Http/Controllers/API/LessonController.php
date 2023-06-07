<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::all();
        return response()->json($lessons);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'major_id' => 'required',
                'grade_id' => 'required',
                'name' => 'required',
                'weekly_frequency' => 'required',
            ]);

            $validatedData['created_by'] = Auth::id();

            $lesson = Lesson::create($validatedData);

            return response()->json(['message' => 'Ders başarıyla oluşturuldu', 'data' => $lesson]);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);

            return response()->json($lesson);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ders bulunamadı'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $lesson = Lesson::findOrFail($id);
            try {
                $validatedData = $request->validate([
                    'major_id' => 'required',
                    'grade_id' => 'required',
                    'name' => 'required',
                    'weekly_frequency' => 'required',
                ]);

                $validatedData['updated_by'] = Auth::id();

                $lesson->update($validatedData);
                return response()->json(['message' => 'Ders bilgileri başarıyla güncellendi', 'data' => $lesson]);
            } catch (ValidationException $exception) {
                return response()->json(['errors' => $exception->errors()], 422);
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ders bulunamadı'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);
            $lesson->delete();
            return response()->json(['message' => 'Ders başarıyla silindi'], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ders bulunamadı'], 404);
        }
    }
}
