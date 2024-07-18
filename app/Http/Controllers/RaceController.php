<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Race::query();

        if ($request->has('circuitName')) {
                $query->whereHas('circuit', function ($subquery) use ($request) {
                $subquery->where('circuitName', 'like', '%' . $request->input('circuitName') . '%');
            });
        }

        if ($request->has('season')) {
            $query->where('season', $request->input('season'));
        }

        $races = $query->paginate(10);

        $seasons = Race::distinct()->orderBy('season', 'desc')->pluck('season');

        return view('races', compact('races', 'seasons'));
    }
}
