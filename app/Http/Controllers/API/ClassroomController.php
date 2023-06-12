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
    /**
     * @OA\Get(
     *     path="/api/classrooms",
     *     summary="Get all classrooms",
     *     tags={"Classrooms"},
     *     @OA\Response(response="200", description="OK"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index()
    {
        $classrooms = Classroom::all();
        return response()->json($classrooms);
    }
    /**
     * @OA\Post(
     *     path="/api/classrooms",
     *     summary="Create a new classroom",
     *     tags={"Classrooms"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Classroom A"),
     *             @OA\Property(property="grade_id", type="integer", example="1"),
     *             @OA\Property(property="branch_id", type="integer", example="1"),
     *             @OA\Property(property="status", type="string", example="Active")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/api/classrooms/{id}",
     *     summary="Get a specific classroom",
     *     tags={"Classrooms"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Classroom not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show($id)
    {
        try {
            $classroom = Classroom::findOrFail($id);

            return response()->json($classroom);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Sınıf bulunamadı'], 404);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/classrooms/{id}",
     *     summary="Update a classroom",
     *     tags={"Classrooms"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Classroom A"),
     *             @OA\Property(property="grade_id", type="integer", example="1"),
     *             @OA\Property(property="branch_id", type="integer", example="1"),
     *             @OA\Property(property="status", type="string", example="Active")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Classroom not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
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
    /**
     * @OA\Delete(
     *     path="/api/classrooms/{id}",
     *     summary="Delete a classroom",
     *     tags={"Classrooms"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="404", description="Classroom not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
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
