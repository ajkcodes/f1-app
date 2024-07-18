<html>
<head>
    <title>Liste der Rennen</title>

    <!-- Styles -->
    <style>
        .header,.race-list{padding:20px}.pagination .active,.race-name{font-weight:700}*{box-sizing:border-box}body{font-family:Arial,sans-serif;background-color:#f4f4f4;margin:0;padding:0;display:flex;justify-content:center;align-items:center;min-height:100vh}.container{width:80%;max-width:800px;background:#fff;box-shadow:0 0 10px rgba(0,0,0,.1);border-radius:8px;overflow:hidden}.header{background-color:#007bff;color:#fff;text-align:center;border-bottom:2px solid #0056b3;font-size:1.5rem}.race-item{background-color:#f9f9f9;margin-bottom:15px;padding:15px;border-radius:5px;transition:background-color .3s}.race-item:hover{background-color:#e0e0e0}.race-name{font-size:1.2rem;margin-bottom:5px}.race-details{font-size:.9rem;color:#666}.pagination{text-align:center;margin-top:20px;margin-bottom:20px}.pagination .page-link{display:inline-block;padding:.5rem .75rem;margin:0 2px;color:#007bff;background-color:#fff;border:1px solid #dee2e6;border-radius:.25rem}.pagination .page-item.disabled .page-link{color:#6c757d;pointer-events:none;background-color:#fff;border-color:#dee2e6}.pagination .page-item.active .page-link{z-index:1;color:#fff;background-color:#007bff;border-color:#007bff}.pagination svg{width:20px;height:20px;vertical-align:middle;margin-bottom:2px}.pagination li{display:inline-block;margin:0 5px;font-size:1rem}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Liste der Rennen
        </div>
        <form action="" method="GET">
            <input type="text" name="circuitName" placeholder="Filter nach Rennstrecke">
            <button type="submit">Filtern</button>
        </form>
        <form action="" method="GET">
            <select name="season">
                <option value="">Alle Jahre</option>
                @foreach ($seasons as $season)
                    <option value="{{ $season }}" {{ request('season') == $season ? 'selected' : '' }}>
                        {{ $season }}
                    </option>
                @endforeach
            </select>
            <button type="submit">Filtern</button>
        </form>
        <div class="race-list">
            @forelse ($races as $race)
                <div class="race-item">
                    <div class="race-name">{{ $race->raceName }}</div>
                    <div class="race-details">
                        <p><strong>Rennstrecke:</strong> <a href="{{ $race->circuit->url }}" target="_blank">{{ $race->circuit->circuitName }}</a></p>
                        <p><strong>Standort:</strong> {{ $race->circuit->location->country }} - {{ $race->circuit->location->locality }}</p>
                        <p><strong>Saison:</strong> {{ $race->season }}</p>
                        <p><strong>Runde:</strong> {{ $race->round }}</p>
                        <p><strong>Datum:</strong> {{ $race->date }}</p>
                        <p><strong>URL:</strong> <a href="{{ $race->url }}">{{ $race->url }}</a></p>
                    </div>
                </div>
            @empty
                <p>Keine Rennen gefunden.</p>
            @endforelse
        </div>

        <!-- Pagination Links -->
        <div class="pagination">
            {{ $races->links() }}
        </div>
    </div>
</body>
</html>
