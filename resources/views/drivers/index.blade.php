@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Fahrer</h1>

        <form action="{{ route('drivers.index') }}" method="GET">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}">
            </div>
            <div class="form-group">
                <label for="nationality">Nationalität</label>
                <select name="nationality" id="nationality" class="form-control">
                    <option value="">Wähle Nationalität</option>
                    @foreach($nationalities as $nationality)
                        <option value="{{ $nationality }}" {{ request('nationality') == $nationality ? 'selected' : '' }}>
                            {{ $nationality }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filtern</button>
        </form>

        @if($drivers->count())
            <table class="table table-borderer mt-3">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Nationalität</th>
                        <th>Geburtstag</th>
                        <th>URL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drivers as $driver)
                        <tr>
                            <td><h4>{{ $driver->givenName }} {{ $driver->familyName }}</h4></td>
                            <td>{{ $driver->nationality }}</td>
                            <td>{{ \Carbon\Carbon::parse($driver->dateOfBirth)->format('d.m.Y') }}</td>
                            <td><a href="{{ $driver->url }}">{{ $driver->url }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center pagination">
                {{ $drivers->appends(request()->query())->links() }}
            </div>
        @else
            <p>Keine Fahrer gefunden.</p>
        @endif
    </div>
@endsection
