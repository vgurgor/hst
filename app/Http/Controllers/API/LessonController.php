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
    /**
     * @OA\Get(
     *     path="/api/lessons",
     *     summary="Get all lessons",
     *     tags={"Lessons"},
     *     @OA\Response(response="200", description="OK"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index()
    {
        $lessons = Lesson::all();
        return response()->json($lessons);
    }
    /**
     * @OA\Post(
     *     path="/api/lessons",
     *     summary="Create a new lesson",
     *     tags={"Lessons"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="major_id", type="integer", example="1"),
     *             @OA\Property(property="grade_id", type="integer", example="1"),
     *             @OA\Property(property="name", type="string", example="Math"),
     *             @OA\Property(property="weekly_frequency", type="integer", example="3")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="422", description="Validation error"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/api/lessons/{id}",
     *     summary="Get a specific lesson",
     *     tags={"Lessons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Lesson ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Lesson not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);

            return response()->json($lesson);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ders bulunamadı'], 404);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/lessons/{id}",
     *     summary="Update a lesson",
     *     tags={"Lessons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Lesson ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="major_id", type="integer", example="1"),
     *             @OA\Property(property="grade_id", type="integer", example="1"),
     *             @OA\Property(property="name", type="string", example="Science"),
     *             @OA\Property(property="weekly_frequency", type="integer", example="4")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Lesson not found"),
     *     @OA\Response(response="422", description="Validation error"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
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
    /**
     * @OA\Delete(
     *     path="/api/lessons/{id}",
     *     summary="Delete a lesson",
     *     tags={"Lessons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Lesson ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="404", description="Lesson not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
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
