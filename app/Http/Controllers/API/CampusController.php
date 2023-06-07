<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CampusController extends Controller
{
    public function index()
    {
        $campuses = Campus::all();
        return response()->json($campuses);
    }

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

    public function show($id)
    {
        try {
            $campus = Campus::findOrFail($id);

            return response()->json($campus);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Kampüs bulunamadı'], 404);
        }
    }

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
