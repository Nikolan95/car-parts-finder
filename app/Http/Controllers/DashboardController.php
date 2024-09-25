<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function getVehicleInfo(Request $request)
    {
        $vin = $request->vin;

        $vehicleExists = Vehicle::where('vin', $vin)->get();

        if (!$vehicleExists->isEmpty()){

        }
        else{
            $payload = [
                'token' => config('website.search_token'),
                'formValues' => [
                    [
                        'name' => 'IdentString',
                        'value' => $vin
                    ]
                ]
            ];

            $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
                ->withHeaders([
                    'accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post('https://oem-api.yqservice.eu/restApi/v2/getVehicleInfo', $payload);

            $data = $response->json();

            $type = $data['data']['type'] ?? '';
            $brand = $data['data']['brand'] ?? '';
            $model = $data['data']['model'] ?? '';
            $date = $data['data']['attributes'][0]['values'][0] ?? '';
            $manufactured = $data['data']['attributes'][1]['values'][0] ?? '';
            $prodrange = $data['data']['attributes'][2]['values'][0] ?? '';
            $market = $data['data']['attributes'][3]['values'][0] ?? '';
            $engine = $data['data']['attributes'][4]['values'][0] ?? '';
            $engineNr = $data['data']['attributes'][5]['values'][0] ?? '';
            $engineInfo = $data['data']['attributes'][6]['values'][0] ?? '';
            $transmission = $data['data']['attributes'][7]['values'][0] ?? '';
            $framecolor = $data['data']['attributes'][8]['values'][0] ?? '';
            $trimcolor = $data['data']['attributes'][9]['values'][0] ?? '';
            $filterLevel = $data['data']['sysProperties'][0]['value'] ?? '';
            $catalogInfoToken = $data['data']['links'][0]['token'] ?? '';
            $catalogShortToken = $data['data']['links'][1]['token'] ?? '';
            $token = $data['data']['token'] ?? '';
            $partApplicabilityToken = $data['data']['forms'][0]['token'] ?? '';
            $navigationTreeToken = $data['data']['navigationLinks'][0]['token'] ?? '';
            $groupsToken = $data['data']['navigationLinks'][1]['token'] ?? '';


            $newVehicle = Vehicle::create([
                'vin' => $vin,
                'type' => $type,
                'brand' => $brand,
                'model' => $model,
                'date' => $date,
                'manufactured' => $manufactured,
                'prodrange' => $prodrange,
                'market' => $market,
                'engine' => $engine,
                'engine_nr' => $engineNr,
                'engine_info' => $engineInfo,
                'transmission' => $transmission,
                'framecolor' => $framecolor,
                'trimcolor' => $trimcolor,
                'filter_level' => $filterLevel,
                'catalogInfoToken' => $catalogInfoToken,
                'catalogShortToken' => $catalogShortToken,
                'token' => $token,
                'partApplicabilityToken' => $partApplicabilityToken,
                'navigationTreeToken' => $navigationTreeToken,
                'groupsToken' => $groupsToken
            ]);
        }

    }
}
