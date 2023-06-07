<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LessonSlot;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LessonSlotController extends Controller
{
    public function index()
    {
        $lessonSlots = LessonSlot::all();
        return response()->json($lessonSlots);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'classroom_id' => 'required|exists:classrooms,id',
                'day' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);

            $lessonSlot = LessonSlot::create($validatedData);

            return response()->json(['message' => 'Ders slotu başarıyla oluşturuldu', 'data' => $lessonSlot]);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            $lessonSlot = LessonSlot::findOrFail($id);

            return response()->json($lessonSlot);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ders slotu bulunamadı'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $lessonSlot = LessonSlot::findOrFail($id);

            $validatedData = $request->validate([
                'classroom_id' => 'required|exists:classrooms,id',
                'day' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);

            $lessonSlot->update($validatedData);

            return response()->json(['message' => 'Ders slotu başarıyla güncellendi', 'data' => $lessonSlot]);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ders slotu bulunamadı'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $lessonSlot = LessonSlot::findOrFail($id);
            $lessonSlot->delete();
            return response()->json(['message' => 'Ders slotu başarıyla silindi'], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ders slotu bulunamadı'], 404);
        }
    }
}
