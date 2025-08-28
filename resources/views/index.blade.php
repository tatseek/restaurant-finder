<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Finder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2em;
        }
        input[type="text"] {
            padding: 6px;
            width: 250px;
        }
        button {
            padding: 6px 12px;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .restaurant {
            margin-top: 15px;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .restaurant h3 {
            margin: 0 0 5px;
        }
    </style>
</head>
<body>
    <h1>Find Restaurants in Your City</h1>

    <form action="{{ route('search') }}" method="POST">
        @csrf
        <label for="city">Enter City:</label>
        <input type="text" name="city" id="city" required>
        <button type="submit">Search</button>
    </form>

    {{-- Error message --}}
    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Results --}}
    @isset($restaurants)
        <h2>Top 10 Restaurants in {{ $city }}</h2>
        @forelse ($restaurants as $restaurant)
            <div class="restaurant">
                <h3>{{ $restaurant['name'] }}</h3>
                <p><strong>Address:</strong> {{ $restaurant['address'] }}</p>
                <p><strong>Rating:</strong> {{ $restaurant['rating'] }}</p>
            </div>
        @empty
            <p>No restaurants found.</p>
        @endforelse
    @endisset
</body>
</html>

