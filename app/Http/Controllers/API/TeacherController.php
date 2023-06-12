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
    /**
     * @OA\Get(
     *     path="/api/teachers",
     *     summary="Get all teachers",
     *     tags={"Teachers"},
     *     @OA\Response(response="200", description="OK"),
     * )
     */
    public function index()
    {
        $teachers = Teacher::all();
        return response()->json($teachers);
    }
    /**
     * @OA\Post(
     *     path="/api/teachers",
     *     summary="Create a new teacher",
     *     tags={"Teachers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="majors", type="string")
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
    /**
     * @OA\Get(
     *     path="/api/teachers/{id}",
     *     summary="Get a specific teacher",
     *     tags={"Teachers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Teacher ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Teacher not found")
     * )
     */
    public function show($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);

            return response()->json($teacher);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Öğretmen bulunamadı'], 404);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/teachers/{id}",
     *     summary="Update a teacher",
     *     tags={"Teachers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Teacher ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="majors", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Teacher not found"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
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
    /**
     * @OA\Delete(
     *     path="/api/teachers/{id}",
     *     summary="Delete a teacher",
     *     tags={"Teachers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Teacher ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="No Content"),
     *     @OA\Response(response="404", description="Teacher not found")
     * )
     */
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
