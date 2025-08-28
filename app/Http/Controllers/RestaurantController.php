<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RestaurantController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255'
        ]);

        $city = $request->input('city');

        // Step 1: Get OSM area ID from Nominatim
        $response = Http::withHeaders([
            'User-Agent' => 'LaravelRestaurantApp/1.0 (das31arunima@gmail.com)'
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $city,
            'format' => 'json',
            'addressdetails' => 1,
            'limit' => 1,
        ]);

        $areaData = $response->json();

        if (empty($areaData)) {
            return back()->withErrors(['city' => 'City not found on OpenStreetMap.']);
        }

        $osmType = $areaData[0]['osm_type'];
        $osmId = $areaData[0]['osm_id'];

        // Step 2: Convert OSM ID to Overpass area ID
        switch ($osmType) {
            case 'relation':
                $areaId = 3600000000 + $osmId;
                break;
            case 'way':
                $areaId = 2400000000 + $osmId;
                break;
            case 'node':
                $areaId = 2000000000 + $osmId;
                break;
            default:
                return back()->withErrors(['city' => 'Unsupported OSM type.']);
        }

        // Step 3: Build Overpass API query
        $overpassQuery = "
            [out:json][timeout:25];
            area({$areaId})->.searchArea;
            (
              node[\"amenity\"=\"restaurant\"](area.searchArea);
              way[\"amenity\"=\"restaurant\"](area.searchArea);
              relation[\"amenity\"=\"restaurant\"](area.searchArea);
            );
            out center 10;
        ";

        $overpassResponse = Http::get('https://overpass-api.de/api/interpreter', [
            'data' => $overpassQuery
        ]);

        $data = $overpassResponse->json();

        if (!isset($data['elements']) || empty($data['elements'])) {
            return back()->withErrors(['city' => 'No restaurants found in this city.']);
        }

        // Step 4: Extract restaurant info
        $restaurants = collect($data['elements'])->take(10)->map(function ($place) {
            $name = $place['tags']['name'] ?? 'N/A';

            $address = 'N/A';
            if (isset($place['tags']['addr:street'])) {
                $address = $place['tags']['addr:street'];
                if (isset($place['tags']['addr:housenumber'])) {
                    $address = $place['tags']['addr:housenumber'] . ' ' . $address;
                }
                if (isset($place['tags']['addr:city'])) {
                    $address .= ', ' . $place['tags']['addr:city'];
                }
            } elseif (isset($place['lat'], $place['lon'])) {
                $address = 'Lat: ' . $place['lat'] . ', Lon: ' . $place['lon'];
            } elseif (isset($place['center']['lat'], $place['center']['lon'])) {
                $address = 'Lat: ' . $place['center']['lat'] . ', Lon: ' . $place['center']['lon'];
            }

            return [
                'name' => $name,
                'address' => $address,
                'rating' => 'N/A', // OSM doesn't provide ratings
            ];
        });

        return view('index', [
            'restaurants' => $restaurants,
            'city' => $city,
        ]);
    }
}

