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
    /**
     * @OA\Get(
     *     path="/api/grades",
     *     summary="Get all grades",
     *     tags={"Grades"},
     *     @OA\Response(response="200", description="OK"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index()
    {
        $grades = Grade::all();
        return response()->json($grades);
    }
    /**
     * @OA\Post(
     *     path="/api/grades",
     *     summary="Create a new grade",
     *     tags={"Grades"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Grade A")
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
                'name' => 'required'
            ]);

            $validatedData['created_by'] = Auth::id();

            $grade = Grade::create($validatedData);

            return response()->json(['message' => 'Düzey başarıyla oluşturuldu', 'data' => $grade]);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/grades/{id}",
     *     summary="Get a specific grade",
     *     tags={"Grades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Grade ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Grade not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show($id)
    {
        try {
            $grade = Grade::findOrFail($id);

            return response()->json($grade);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Düzey bulunamadı'], 404);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/grades/{id}",
     *     summary="Update a grade",
     *     tags={"Grades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Grade ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Grade A")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Grade not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $grade = Grade::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required'
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
    /**
     * @OA\Delete(
     *     path="/api/grades/{id}",
     *     summary="Delete a grade",
     *     tags={"Grades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Grade ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="404", description="Grade not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
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
