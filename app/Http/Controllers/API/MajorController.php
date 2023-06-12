<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class MajorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/majors",
     *     summary="Get all majors",
     *     tags={"Majors"},
     *     @OA\Response(response="200", description="OK"),
     * )
     */
    public function index()
    {
        $majors = Major::all();
        return response()->json($majors);
    }
    /**
     * @OA\Post(
     *     path="/api/majors",
     *     summary="Create a new major",
     *     tags={"Majors"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="status", type="string")
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
                'name' => 'required',
                'status' => 'required'
            ]);

            $validatedData['created_by'] = Auth::id();

            $major = Major::create($validatedData);

            return response()->json(['message' => 'Major created successfully', 'data' => $major]);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/majors/{id}",
     *     summary="Get a specific major",
     *     tags={"Majors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Major ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Major not found")
     * )
     */
    public function show($id)
    {
        try {
            $major = Major::findOrFail($id);

            return response()->json($major);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Major not found'], 404);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/majors/{id}",
     *     summary="Update a major",
     *     tags={"Majors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Major ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Major not found"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $major = Major::findOrFail($id);
            try {
                $validatedData = $request->validate([
                    'name' => 'required',
                    'status' => 'required'
                ]);

                $validatedData['updated_by'] = Auth::id();

                $major->update($validatedData);
                return response()->json(['message' => 'Major updated successfully', 'data' => $major]);
            } catch (ValidationException $exception) {
                return response()->json(['errors' => $exception->errors()], 422);
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Major not found'], 404);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/majors/{id}",
     *     summary="Delete a major",
     *     tags={"Majors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Major ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="404", description="Major not found")
     * )
     */
    public function destroy($id)
    {
        try {
            $major = Major::findOrFail($id);
            $major->delete();
            return response()->json(['message' => 'Major deleted successfully'], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Major not found'], 404);
        }
    }
}
