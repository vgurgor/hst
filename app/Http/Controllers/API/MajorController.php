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
    public function index()
    {
        $majors = Major::all();
        return response()->json($majors);
    }

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

    public function show($id)
    {
        try {
            $major = Major::findOrFail($id);

            return response()->json($major);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Major not found'], 404);
        }
    }

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
