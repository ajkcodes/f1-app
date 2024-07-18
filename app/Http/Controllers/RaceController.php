<?php

namespace App\Http\Controllers;

use App\Models\Circuit;
use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function index(Request $request)
    {
        // Fetch distinct seasons
        $seasons = Race::distinct()->orderBy('season', 'desc')->pluck('season');

        // Fetch distinct circuitNames
        $circuits = Circuit::distinct()->orderBy('circuitName', 'asc')->pluck('circuitName');

        $query = Race::query();

        if ($request->filled('circuitName')) {
                $query->whereHas('circuit', function ($subquery) use ($request) {
                $subquery->where('circuitName', 'like', '%' . $request->input('circuitName') . '%');
            });
        }

        if ($request->filled('season')) {
            $query->where('season', $request->input('season'));
        }

        $races = $query->paginate(10);

        return view('races', compact('races', 'seasons', 'circuits'));
    }
}
