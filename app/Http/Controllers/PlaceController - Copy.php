<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;

class PlaceController extends Controller
{
    public function getNearbyPlaces(Request $request)
    {
        $latitude = $request->input('lat');
        $longitude = $request->input('lon');
        $radius = 5000; // Specify the desired radius in meters

        $client = new Client();

        // Make a GET request to the OpenStreetMap API using Guzzle
        $response = $client->get("https://nominatim.openstreetmap.org/reverse", [
            'query' => [
                'format' => 'jsonv2',
                'lat' => $latitude,
                'lon' => $longitude,
            ]
        ]);

        $responseBody = json_decode($response->getBody(), true);

        $placeData = [
            'name' => $responseBody['display_name'],
            'latitude' => $responseBody['lat'],
            'longitude' => $responseBody['lon'],
            // Add any additional relevant information from the OpenStreetMap response
        ];

        // Make another GET request to retrieve nearby places
        $nearbyPlacesResponse = $client->get("https://nominatim.openstreetmap.org/search", [
            'query' => [
                'format' => 'jsonv2',
                'lat' => $latitude,
                'lon' => $longitude,
                'radius' => $radius,
            ]
        ]);

        $nearbyPlaces = json_decode($nearbyPlacesResponse->getBody(), true);

        // Process the $nearbyPlaces array to extract relevant information as per your requirements

        $placeData['nearby_places'] = $nearbyPlaces;

        return response()->json($placeData);
    }
}

