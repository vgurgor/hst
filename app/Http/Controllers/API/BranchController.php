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
    public function index()
    {
        $branches = Branch::all();
        return response()->json($branches);
    }

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

    public function show($id)
    {
        try {
            $branch = Branch::findOrFail($id);

            return response()->json($branch);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Şube bulunamadı'], 404);
        }
    }

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
