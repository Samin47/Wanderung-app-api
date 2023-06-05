<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class PlaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    public function getNearbyPlaces(Request $request)
    {

        $latitude = $request->input('lat');
        $longitude = $request->input('long');
        $radius = $request->input('radius', 5000); // Set default radius to 5 kilometres if not provided


        $client = new Client();
        $response = $client->get('https://api.foursquare.com/v3/places/search', [
            'headers' => [
                'Authorization' => 'fsq3e6QuXTIDlMxJz2ybXlt8rb3ujIFr1diD2Ox2cMvhezY=',
                'Accept' => 'application/json',
            ],
            'query' => [
                'll' => $latitude.','.$longitude,
                'radius' => $radius,
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $places = $data['results'];

        $nearbyPlaces = [];
        foreach ($places as $place) {
            $name = $place['name'];
            $category = isset($place['categories'][0]['name']) ? $place['categories'][0]['name'] : 'N/A';
            $address = $place['location']['formatted_address'] ? $place['location']['formatted_address'] : 'N/A';
            $distance = $place['distance'];
            $placeLatitude = $place['geocodes']['main']['latitude'];
            $placeLongitude = $place['geocodes']['main']['longitude'];
            $country = $place['location']['country'];

            $nearbyPlaces[] = [
                'name' => $name,
                'category' => $category,
                'address' => $address,
                'distance from given coordinates' => $distance . ' meters',
                'latitude' => $placeLatitude,
                'longitude' => $placeLongitude,
                'country' => $country,
            ];
        }

        return response()->json($nearbyPlaces);
    }
}

