<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Validator;


class PlaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }



    public function getNearbyPlaces(Request $request)
    {
        // Call the validation function to validate the input
        $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
  
        try {
            $places = $this->fetchNearbyPlaces($request);  // Call the method to make the API call

            if (empty($places)) {
                return response()->json(['message' => 'No nearby places found.'], 200);
            }

            return response()->json($places);

        } catch (RequestException $e) {
            // Handle Guzzle request exceptions here
            $statusCode = $e->getCode();
            $errorMessage = $e->getMessage();
            return response()->json(['error' => $errorMessage], $statusCode);

        } catch (\Exception $e) {
            // Handle other exceptions here
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    private function fetchNearbyPlaces(Request $request)
    {
        $latitude = $request->input('lat');
        $longitude = $request->input('long');
        $radius = $request->input('radius', 5000);
        $limit = $request->input('limit', 10);


        $client = new Client();
        $response = $client->get('https://api.foursquare.com/v3/places/search', [
            'headers' => [
                'Authorization' => env('FOURSQUARE_API_TOKEN'),
                'Accept' => 'application/json',
            ],
            'query' => [
                'll' => $latitude . ',' . $longitude,
                'radius' => $radius,
                'limit' => $limit,
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $places = $data['results'];

        return $this->formatPlaces($places);
    }

    
    private function validateInput(Request $request)
    {
        return Validator::make($request->all(), [
            'lat' => ['required', 'numeric', 'min:-90', 'max:90'],
            'long' => ['required', 'numeric', 'min:-180', 'max:180'],
            'radius' => ['sometimes', 'numeric', 'min:1', 'max:100000'],
            'limit' => ['sometimes', 'numeric', 'min:1', 'max:50']
        ]);
    }

    private function formatPlaces($places)
    {
        $formattedPlaces = [];

        foreach ($places as $place) {
            $name = $place['name'];
            $category = isset($place['categories'][0]['name']) ? $place['categories'][0]['name'] : 'N/A';
            $address = $place['location']['formatted_address'] ? $place['location']['formatted_address'] : 'N/A';
            $distance = $place['distance'];
            $placeLatitude = $place['geocodes']['main']['latitude'];
            $placeLongitude = $place['geocodes']['main']['longitude'];
            $country = $place['location']['country'];

            $formattedPlaces[] = [
                'name' => $name,
                'category' => $category,
                'address' => $address,
                'distance from given coordinates' => $distance . ' meters',
                'latitude' => $placeLatitude,
                'longitude' => $placeLongitude,
                'country' => $country,
            ];
        }
        return $formattedPlaces;
    }

}

