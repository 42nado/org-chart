<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $positions = Position::query();

        if ($request->has('search')) {
            $positions->where('name', 'like', '%' . $request->search . '%');
        }

        $positions = $positions->orderBy('name')->get();

        return response()->json($positions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:positions,name',
            'report_to' => 'nullable|exists:positions,id',
        ]);

        $position = Position::create($validated);

        return response()->json($position, 201);
    }

    public function show($id)
    {
        $position = Position::findOrFail($id);
        return response()->json($position);
    }

    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|unique:positions,name,' . $position->id,
            'report_to' => 'nullable|exists:positions,id',
        ]);

         $position->update($validated);

        return response()->json($position);
    }


    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return response()->json(null, 204);
    }
}