<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LessonSlot;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LessonSlotController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/lesson-slots",
     *     summary="Get all lesson slots",
     *     tags={"Lesson Slots"},
     *     @OA\Response(response="200", description="OK"),
     * )
     */
    public function index()
    {
        $lessonSlots = LessonSlot::all();
        return response()->json($lessonSlots);
    }
    /**
     * @OA\Post(
     *     path="/api/lesson-slots",
     *     summary="Create a new lesson slot",
     *     tags={"Lesson Slots"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="campus_id", type="integer", example="1"),
     *             @OA\Property(property="day", type="string", example="Monday"),
     *             @OA\Property(property="start_time", type="string", example="09:00"),
     *             @OA\Property(property="end_time", type="string", example="10:30")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'campus_id' => 'required|exists:campuses,id',
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
    /**
     * @OA\Get(
     *     path="/api/lesson-slots/{id}",
     *     summary="Get a specific lesson slot",
     *     tags={"Lesson Slots"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Lesson Slot ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Lesson slot not found")
     * )
     */
    public function show($id)
    {
        try {
            $lessonSlot = LessonSlot::findOrFail($id);

            return response()->json($lessonSlot);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ders slotu bulunamadı'], 404);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/lesson-slots/{id}",
     *     summary="Update a lesson slot",
     *     tags={"Lesson Slots"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Lesson Slot ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="campus_id", type="integer", example="1"),
     *             @OA\Property(property="day", type="string", example="Tuesday"),
     *             @OA\Property(property="start_time", type="string", example="10:00"),
     *             @OA\Property(property="end_time", type="string", example="11:30")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Lesson slot not found"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $lessonSlot = LessonSlot::findOrFail($id);

            $validatedData = $request->validate([
                'campus_id' => 'required|exists:campuses,id',
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
    /**
     * @OA\Delete(
     *     path="/api/lesson-slots/{id}",
     *     summary="Delete a lesson slot",
     *     tags={"Lesson Slots"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Lesson Slot ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="404", description="Lesson slot not found")
     * )
     */
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
