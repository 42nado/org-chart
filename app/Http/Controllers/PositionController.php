<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
public function index(Request $request)
{
    $positions = Position::query();

    // Search functionality
    if ($request->has('search')) {
        $positions->where('name', 'like', '%' . $request->search . '%');
    }

    // Sorting functionality
    if ($request->has('sort')) {
        $sort = $request->sort;

        switch ($sort) {
            case 'name-asc':
                $positions->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $positions->orderBy('name', 'desc');
                break;
            case 'report_to':
                $positions->leftJoin('positions as parent', 'positions.report_to', '=', 'parent.id')
                          ->orderBy('parent.name');
                break;
            default:
                $positions->orderBy('name');
                break;
        }
    } else {
        // Default sorting by name if no sort parameter is provided
        $positions->orderBy('name');
    }

    // Include the parent position relationship
    $positions = $positions->with('parentPosition')->get();

    return response()->json($positions);
}



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:positions,name',
            'report_to' => 'nullable|exists:positions,id',
        ]);

        //only allow one null report_to position
        if ($validated['report_to'] === null && Position::whereNull('report_to')->exists()) {
            return response()->json(['error' => 'Only one position can have a null report_to'], 400);
        }

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
