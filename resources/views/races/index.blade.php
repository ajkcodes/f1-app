@extends('layouts.app')

@section('title', 'Races')

@section('content')
    <div class="container mt-5">
        <h1>Rennen</h1>
        <form action="{{ route('races.index') }}" method="GET">
            <div class="form-group">
                <label for="circuitName">Rennstrecke</label>
                <select name="circuitName" id="circuitName" class="form-control">
                    <option value="">Rennstrecke</option>
                    @foreach($circuits as $circuit)
                        <option value="{{ $circuit }}" {{ request('circuitName') == $circuit ? 'selected' : '' }}>
                            {{ $circuit }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="season">Jahr</label>
                <select name="season" class="form-control">
                    <option value="">Alle Jahre</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season }}" {{ request('season') == $season ? 'selected' : '' }}>
                            {{ $season }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filtern</button>
        </form>

        @if($races->count())
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Saison</th>
                        <th>Runde</th>
                        <th>Name</th>
                        <th>Datum</th>
                        <th>Rennstrecke</th>
                        <th>Standort</th>
                        <th>URL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($races as $race)
                        <tr>
                            <td>{{ $race->season }}</td>
                            <td>{{ $race->round }}</td>
                            <td>{{ $race->raceName }}</td>
                            <td>{{ \Carbon\Carbon::parse($race->date)->format('d.m.Y') }}</td>
                            <td><a href="{{ $race->circuit->url }}" target="_blank">{{ $race->circuit->circuitName }}</a></td>
                            <td>{{ $race->circuit->location->country }} - {{ $race->circuit->location->locality }}</td>
                            <td><a href="{{ $race->url }}" target="_blank">{{ $race->url }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center pagination">
                {{ $races->appends(request()->query())->links() }}
            </div>
        @else
            <p>Keine Rennen gefunden.</p>
        @endif
    </div>
@endsection
