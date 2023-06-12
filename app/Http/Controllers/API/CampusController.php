<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
/**
 * @OA\Info(
 *     title="HST API Documentation",
 *     version="1.0.0",
 *     description="Application API services documentation page",
 *     contact={
 *         "email": "volkangurgor@gmail.com"
 *     }
 * )
 */
class CampusController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/campuses",
     *     summary="Get all campuses",
     *     tags={"Campuses"},
     *     @OA\Response(response="200", description="OK"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index()
    {
        $campuses = Campus::all();
        return response()->json($campuses);
    }

    /**
     * @OA\Post(
     *     path="/api/campuses",
     *     summary="Create a new campus",
     *     tags={"Campuses"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Campus A"),
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
                'status' => 'required'
            ]);

            $validatedData['created_by'] = Auth::id();

            $campus = Campus::create($validatedData);

            return response()->json(['message' => 'Kampüs başarıyla oluşturuldu', 'data' => $campus]);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/campuses/{id}",
     *     summary="Get a specific campus",
     *     tags={"Campuses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Campus ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Campus not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show($id)
    {
        try {
            $campus = Campus::findOrFail($id);

            return response()->json($campus);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Kampüs bulunamadı'], 404);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/campuses/{id}",
     *     summary="Update a campus",
     *     tags={"Campuses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Campus ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Campus A"),
     *             @OA\Property(property="status", type="string", example="Inactive")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Campus not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $campus = Campus::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required',
                'status' => 'required'
            ]);

            $validatedData['updated_by'] = Auth::id();

            $campus->update($validatedData);

            return response()->json(['message' => 'Kampüs bilgileri başarıyla güncellendi', 'data' => $campus]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Kampüs bulunamadı'], 404);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/campuses/{id}",
     *     summary="Delete a campus",
     *     tags={"Campuses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Campus ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="404", description="Campus not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function destroy($id)
    {
        try {
            $campus = Campus::findOrFail($id);
            $campus->delete();

            return response()->json(['message' => 'Kampüs başarıyla silindi'], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Kampüs bulunamadı'], 404);
        }
    }
}
