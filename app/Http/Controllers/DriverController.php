<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        // Fetch distinct nationalities for filter
        $nationalities = Driver::distinct()->orderBy('nationality', 'asc')->pluck('nationality');

        // Query drivers with filters
        $query = Driver::query();

        // Filter by nationality
        if ($request->has('nationality')) {
            $nationality = $request->input('nationality');
            $query->where('nationality', 'like', '%' . $nationality . '%');
        }

        // Filter by name if it is not null or empty
        if ($request->filled('name')) {
            $name = $request->input('name');
            $query->where(function ($q) use ($name) {
                $q->where('givenName', 'like', '%' . $name . '%')
                  ->orWhere('familyName', 'like', '%' . $name . '%');
            });
        }

        $drivers = $query->paginate(10);

        return view('drivers.index', compact('drivers', 'nationalities'));
    }
}
