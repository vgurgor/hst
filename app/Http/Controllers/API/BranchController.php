<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BranchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/branches",
     *     summary="Get all branches",
     *     tags={"Branches"},
     *     @OA\Response(response="200", description="OK"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index()
    {
        $branches = Branch::all();
        return response()->json($branches);
    }
    /**
     * @OA\Post(
     *     path="/api/branches",
     *     summary="Create a new branch",
     *     tags={"Branches"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Branch A"),
     *             @OA\Property(property="type", type="string", example="Type A"),
     *             @OA\Property(property="campus_id", type="integer", example="1")
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
                'type' => 'required',
                'campus_id' => 'required|exists:campuses,id'
            ]);

            $validatedData['created_by'] = Auth::id();

            $branch = Branch::create($validatedData);

            return response()->json(['message' => 'Şube başarıyla oluşturuldu', 'data' => $branch]);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/branches/{id}",
     *     summary="Get a specific branch",
     *     tags={"Branches"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Branch ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Branch not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show($id)
    {
        try {
            $branch = Branch::findOrFail($id);

            return response()->json($branch);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Şube bulunamadı'], 404);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/branches/{id}",
     *     summary="Update a branch",
     *     tags={"Branches"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Branch ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Branch A"),
     *             @OA\Property(property="type", type="string", example="Updated Type A"),
     *             @OA\Property(property="campus_id", type="integer", example="1")
     *         )
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Branch not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $branch = Branch::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required',
                'type' => 'required',
                'campus_id' => 'required|exists:campuses,id'
            ]);

            $validatedData['updated_by'] = Auth::id();

            $branch->update($validatedData);

            return response()->json(['message' => 'Şube bilgileri başarıyla güncellendi', 'data' => $branch]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Şube bulunamadı'], 404);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/branches/{id}",
     *     summary="Delete a branch",
     *     tags={"Branches"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Branch ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="404", description="Branch not found"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function destroy($id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $branch->delete();

            return response()->json(['message' => 'Şube başarıyla silindi'], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Şube bulunamadı'], 404);
        }
    }
}
