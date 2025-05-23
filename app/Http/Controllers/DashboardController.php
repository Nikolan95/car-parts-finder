<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Client;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function catalogInfo()
    {
        return view('dashboard.catalog-info');
    }

    public function getVehicleInfo(Request $request)
    {
        $vin = $request->vin;

        $vehicleExists = Vehicle::where('vin', $vin)->get();

        if (!$vehicleExists->isEmpty()){

        }
        else{
            $payload = [
                'token' => '',
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
                ])->post('https://oem-api.yqservice.eu/restApi/v2/findVehicle', $payload);

            $data = $response->json();
            dd($data);

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

    public function getGroups(Request $request){
        $payload = [
            //token koji smo dobili od getVehicleInfo
            'token' => 'AT0teiUtNHIqUFErMXRvdzs4OT44PzEpenobf31_KjUsQ0YMEwFMNz4-Pz47OW5vdjgsJSpQfnp_dGJmWUtBQUxCTEISHQpbXV1aRkBOKXp6Gy00K1xlTH1iZQ0bV2c5KS1zJSkmen50LEppe29lZDF6fi1DSFJLTzs7ZGhmciIrfmZqKzF0IxgtZ21ULTRVKWdsdj49Pzw8PTBXdHQYLX16bFMsM3AKeilufk1pe29VKWwjGC1gbXBTLDNXdGkYLSJVKmp7e2QmPSJ9b2RtUywzV3RoGC0iVSp5Z2dXdGIYLUREUkRLSjJhbnQ_PDswODw-V3R0GC1oe1QtNFUpMARmcnN0KnIAAAAAx7tL-w==',
            'formValues' => [
                [
                    'name' => 'IdentString',
                    'value' => ''
                ]
            ]
        ];

        $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
            ->withHeaders([
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://oem-api.yqservice.eu/restApi/v2/getGroups', $payload);

        $data = $response->json();
        dd($data);
        $allGroups = [];
        $mainPartsGroups = $data['data']['children'];
        foreach ($mainPartsGroups as $mainPartGroup) {
            if (isset($mainPartGroup['links']) && !empty($mainPartGroup['links'])) {
                $allGroups[$mainPartGroup['name']] = $mainPartGroup['links'];
            }
            else {
                foreach ($mainPartGroup['children'] as $subLevelGroup) {
                    if (isset($subLevelGroup['links']) && !empty($subLevelGroup['links'])) {
                        $allGroups[$subLevelGroup['name']] = $subLevelGroup['links'];
                    }
                    else {
                        foreach ($subLevelGroup['children'] as $subSecondLevelGroup) {
                            if (isset($subSecondLevelGroup['links']) && !empty($subSecondLevelGroup['links'])) {
                                $allGroups[$subSecondLevelGroup['name']] = $subSecondLevelGroup['links'];
                            }
                            else {
                                foreach ($subSecondLevelGroup['children'] as $subThirdLevelGroup) {
                                    if (isset($subThirdLevelGroup['links']) && !empty($subThirdLevelGroup['links'])) {
                                        $allGroups[$subThirdLevelGroup['name']] = $subThirdLevelGroup['links'];
                                    }
                                    else {
                                        foreach ($subThirdLevelGroup['children'] as $subFourthLevelGroup) {
                                            if (isset($subFourthLevelGroup['links']) && !empty($subFourthLevelGroup['links'])) {
                                                $allGroups[$subFourthLevelGroup['name']] = $subFourthLevelGroup['links'];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        dd($allGroups);
    }

    public function getGroupParts(Request $request)
    {
        $payload = [
            //token koji smo dobili od getGroups > data > children > odredjena grupa delova koja nam treba Index niz > links > getGroupParts
            'token' => 'AfHhtunh-L7mnJ3n_biju_f09fL08_3ltrbXs7Gz5vngkoPeprrx8_b28oP18aOnsPHg6eacsrazuK6qlYeNjYCOgI7e0caXkZGWioyC5ba21-H45_eH8aaJwvnHkvCA5eG_6eXqtrK44JSxqqGugOj7_bOLoeb54PT2oqK94e7nh6K2pKv186r54IiG8fL0_6ukqr7u57Kqpuf9uO_U4auhmOH4meWqyKrvnue3sKaZ5aDv1OGvpLSHo7GmxrayuJ7nsqaqmeWgyKq4npmY4bSZm8a2sp-emeaUhoH1qKa58PD0hfP0_PSiptSfnufon56Z5ff31J-e5_6fnpnlqKa68vH3mJ-e5-vGyNThsaaYn57n_cbI1OGemZjh7pmbxrblqJ6ZmOH4mZvGtrmfnpnm756Zm7j35p-emeb5npmbuMS68J6ZmOHumZvGtv6gnpmY4fiZm8a2uZ-emea-nue65-mqvgAAAABi4Ame',
            'formValues' => [
                [
                    'name' => 'IdentString',
                    'value' => ''
                ]
            ]
        ];

        $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
            ->withHeaders([
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://oem-api.yqservice.eu/restApi/v2/getGroupParts', $payload);

        $data = $response->json();
        dd($data);
    }

    public function getGroupPartsAll(Request $request){
        $payload = [
            //token koji smo dobili od getGroups > data > children > odredjena grupa delova koja nam treba Index niz > links > getGroupPartsAll
            'token' => 'ASg4bzA4IWc_RUQ-JGF6Yi4tLCstKiQ8b28OamhqPyA5S1oHf2MoKi8vK1osKHp-aSg5MD9Fa29qYXdzTF5UVFlXWVcHCB9OSEhPU1VbPG9vDjghPi5eKH9QGyAeSylZPDhmMDwzb2thOV1xdjkmPDc_JH85MD9LbnV9KAojdW5sVH45JjxyfGksLj4xOFh9aiIhPn05Jj9XWS4ucnVgKjlhMThtdXphd3NhRz50fkc-JB9vYUY5MEE4aG96H29rYUc-cHtrWH83LA04IWdBOG15dh9va0Y5Z0FGRz5oHxENOCFAQUY5S1oHf2MoKi8vK1osKHp-aShHQEE4N0BCH288eUdAQTghQEIfb2MoKS0uKEdAQmFhDUZHPm55R0BCYXcNRkc-QUZHPjIfEQ04dndBRkc-JB8RDTgqQEFGOTBCHxFzeXVAQUY5JkIfEXNKKS9BRkc-Mh8RDThtf0FGRz4kHxENOCpAQUY5YUJhMCxnOWEAAAAA0rd8uw==',
            'formValues' => [
                [
                    'name' => 'IdentString',
                    'value' => ''
                ]
            ]
        ];

        $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
            ->withHeaders([
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://oem-api.yqservice.eu/restApi/v2/getGroupPartsAll', $payload);

        $data = $response->json();
        dd($data);
    }

    public function getUnitParts()
    {
        $payload = [
            'token' => 'AREBVgkBGF4GfH0HHVhDWxcUFRIUEx0FVlY3U1FTBhkAcmM-RloRExYWEmMVEUNHUBEACQZ8UlZTWE5KdWdtbWBuYG4-MSZ3cXF2amxiBVZWNwEYBxdnEUZpIhknchBgBQFfCQUKVlJYAGRITwAfBRwVBFBHBwgBc1BOGR8vUU1QVGpGBx1YRVkbFBAGDwBmRg4VBExFBx4Bb2cVSkVQEhIHWQ8AU04eVlIBWXkGSkZ5BUAoShN-Bwh_AFZUHihKGVl5Bk5DVWMbAAl_AB9ffwBTQhIoShl-B19_fnkFDCg0fwAfeH9-B3A-MFoREBQXERNkF0xNWxsQeXh_AAl7JihKTkF5eH8AH3smKEoREBcVEBB5eyZWRH9-eQZQQXl7JlZSf355Bn9-eQVWKDR_AEhPf355BUAoNH8AFHh_fgcLJig0AUFLeH9-Bx0mKDQBchcXf355BVYoNH8AU0d_fnkFQCg0fwAUeH9-B1omVhVeXwcIAVdMQ1hOSlh-B01HfgcdJlZZERETEhB-BwsmVhtQRnkGGVl5BRcVGGdDUUV_AB9cJlYeRkp5Bhl-B1wmKDQBVHl4fwAfeyYoSnRmYRYREBQUSEUpExQcFxsQeXsmVkR_fnkGTkF5eyZWUn9-eQYREBcWSUY0f34HCH9-eQUJFzR_fgcef355BSYoNAEOeXh_AEhMJig0ARh5eH8AFHsmKEoPfnl4AUFLeyYoShl-eXgBchcUJig0AQ55eH8AU0QmKDQBGHl4fwAUeyYoSl5-Bwh_AFB7WE40AVl5eH8AUXsmKEoZfnl4AWBoeyYoSg9-eXgBS3l7JlZSEhAWEhURCXsmKEpEfnl4ARgXF0xGWRpfeQYPfgdEJlZSfwBeeH9-B1MmKDQBGHl4fwBjYCYoNAEOeXh_AEh7JihKGUxQSE8OeXsmVg9_fnkGGRYXF0JEFX8AWFleAFgAAAAAfwFcyQ==',
            'formValues' => [
                [
                    'name' => 'IdentString',
                    'value' => ''
                ]
            ]
        ];

        $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
            ->withHeaders([
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://oem-api.yqservice.eu/restApi/v2/getUnitParts', $payload);

        $data = $response->json();
        dd($data);
    }

    public function getCatalogInfo()
    {
        $part = 'Engine';
        $partName = 'engine oil';
        $partIds = [];

        $payload = [
            'token' => 'AcDQh9jQyY_XrazWzImSisbFxMPFwszUh4fmgoCC18jRo6D8_-OowsevsaXExpKQjMTR2Netg4eCiZ-bpLa8vLG_sb_v4PemoKCnu72z1IeH5tDJ1ri0v52GkuTWscTE1NCO2NTbh4OJ0bqUhKGbmd_m1paW1s_QvrW_5YeV0LCVgZOfm5GJn5ukpMXAysTWi4eHz5uX1s_QiKjUwsHl0Mmo18Kv1tr3h8qBl6jXyIio1MbEybaSgJSu0c6N94fPl5uo18iv1o33-eXQkaiprtHOqvf5m6SkqKmu0diq9_mbgZ6oqa7Rzqr3-Zugt6Oprq_W2vf55dCQqKmu0c7AnpSVrq-o15-vqKqJn-Wur9amsbqoqveHla6vqNeBkKiq94eDrq-o18PAw8Ts_eWur9bZrq-o1M7G5a6v1s-ur6jU6OThs6-oqdDfqKr3h82Rr6ip0MmoqveH97CrqKmu0diq9_mbhJqaqa6v1sz3-eXQpKKiqKmux5j_-qTDxMzHxsKq9_mb3q-oqdCHqKr3h4Our6jXpLq6qfjx67uwoKmur9aL94fEj47WiAAAAABSnWXc'
        ];

        $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
            ->withHeaders([
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://oem-api.yqservice.eu/restApi/v2/getNavigationTree', $payload);

        $data = $response->json();


        if (isset($data['data'])){
            foreach ($data['data']['children'] as $child){
                if ($child['name'] == $part){
                    $getUnitsPayload = [];
                    $getUnitsPayload['token'] = $child['links'][0]['token'];

                    $responseUnits = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
                        ->withHeaders([
                            'accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ])->post('https://oem-api.yqservice.eu/restApi/v2/getUnits', $getUnitsPayload);

                    $dataUnits = $responseUnits->json();

                    if (isset($dataUnits['data'])){
                        foreach ($dataUnits['data']['units'] as $unit){
                            if ($unit['name'] == $partName){
                                $getUnitPartsPayload = [];
                                $getUnitPartsPayload['token'] = $unit['links'][1]['token'];

                                $responseUnitsParts = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
                                    ->withHeaders([
                                        'accept' => 'application/json',
                                        'Content-Type' => 'application/json',
                                    ])->post('https://oem-api.yqservice.eu/restApi/v2/getUnitParts', $getUnitPartsPayload);

                                $dataUnitParts = $responseUnitsParts->json();

//                                dd($dataUnitParts);

                                if (isset($dataUnitParts['data']['partSections'][0]['parts'])){
                                    foreach ($dataUnitParts['data']['partSections'][0]['parts'] as $partFinal){
                                        $partIds[] = str_replace(' ', '', $partFinal['partNumber']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        dd($partIds);
    }


    // public function getAftermarketData()
    // {
    //     $url = 'http://host.docker.internal:8080/index.php';

    //     $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password')) // <-- Add basic auth here
    //     ->get($url, [
    //         'task' => 'aftermarket',
    //         'oem' => '402061KA3A',
    //         'brand' => 'NISSAN',
    //         'options' => [
    //             'crosses',
    //             'weights',
    //             'names',
    //             'properties',
    //             'images',
    //         ],
    //     ]);

    //     dd($response);

    //     if ($response->successful()) {
    //         dd($response);
    //         return $response->json(); // returns decoded array
    //     }
    // }

    // public function getAftermarketData()
    // {
    //     $host = 'host.docker.internal'; // If your Laravel is running inside Docker
    //     $loginUrl = "http://$host:8080/index.php?task=aftermarket";
    //     $apiUrl = "http://$host:8080/index.php";

    //     // Step 1: Login (POST form)
    //     $loginResponse = Http::asForm()->post($loginUrl, [
    //         'user' => [
    //             'login' => config('website.http_auth_username'),
    //             'password' => config('website.http_auth_password'),
    //             'backurl' => $loginUrl,
    //         ],
    //         'isAm' => 1, // Important: tells system it's aftermarket login
    //     ]);

    //     if ($loginResponse->status() !== 302) { // Expecting redirect (successful login)
    //         return [
    //             'error' => 'Login failed',
    //             'status' => $loginResponse->status(),
    //             'message' => $loginResponse->body(),
    //         ];
    //     }

    //     $cookies = $loginResponse->cookies();

    //     // Step 2: Fetch Aftermarket data
    //     $response = Http::withCookies($cookies, $host)
    //         ->get($apiUrl, [
    //             'task' => 'aftermarket',
    //             'oem' => '402061KA3A',
    //             'brand' => 'NISSAN',
    //             'options' => [
    //                 'crosses',
    //                 'weights',
    //                 'names',
    //                 'properties',
    //                 'images',
    //             ],
    //         ]);

    //     if ($response->successful()) {
    //         return $response->json();
    //     }

    //     return [
    //         'error' => 'Failed to fetch data',
    //         'status' => $response->status(),
    //         'message' => $response->body(),
    //     ];
    // }

    public function getAftermarketData()
    {
        // Define the base host (replace with your actual host if needed)
        $host = 'host.docker.internal'; // or your real IP if needed
        $loginUrl = "http://$host:8080/index.php?task=login&view=login";  // Login URL for Aftermarket
        $apiUrl = "http://$host:8080/index.php";  // API URL for Aftermarket data
        $cookieJar = new CookieJar();

        // Step 1: Login to Aftermarket
        $client = new \GuzzleHttp\Client();

        // Prepare the POST request with login credentials and back URL
        $response = $client->post($apiUrl.'?task=login&view=login', [
            'form_params' => [
                'user[login]' => 'yq224289',
                'user[password]' => '6jlXvP2tfAeTFL0D',
                'user[backurl]' => $apiUrl.'?task=aftermarket&oem=402061KA3A&brand=NISSAN&options%5B%5D=crosses&options%5B%5D=weights&options%5B%5D=names&options%5B%5D=properties&options%5B%5D=images&&&',
                'isAm' => 1,
            ],
            'allow_redirects' => true,  // Ensure redirects are followed
            'cookies' => $cookieJar,          // Make sure cookies are stored to keep the session
        ]);

        // Check the response
        dd($response);


        // Debugging the login response
        dd($loginResponse->status(), $loginResponse->body(), $loginResponse->cookies());

        // Step 2: Check if login is successful (200 or 302)
        if (!in_array($loginResponse->status(), [200, 302])) {
            return [
                'error' => 'Login failed',
                'status' => $loginResponse->status(),
                'message' => $loginResponse->body(),
            ];
        }

        // Step 3: Extract cookies from the login response
        $cookies = [];
        foreach ($loginResponse->cookies() as $cookie) {
            $cookies[$cookie->getName()] = $cookie->getValue();  // Store cookies for authentication
        }

        dd($cookies); // Debug the cookies to ensure we have captured them correctly

        // Step 4: Fetch Aftermarket data using the cookies
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Laravel HTTP Client)',  // Use a proper User-Agent string
            'Referer' => "http://$host:8080/index.php?task=aftermarket",  // Referrer URL
        ])
        ->withCookies($cookies, $host)  // Attach the cookies for session-based authentication
        ->get($apiUrl, [
            'task' => 'aftermarket',  // Task for Aftermarket data
            'oem' => '402061KA3A',  // Example OEM part number
            'brand' => 'NISSAN',  // Example brand (can be dynamic)
            'options' => [
                'crosses',
                'weights',
                'names',
                'properties',
                'images',
            ],
        ]);

        // Step 5: Check if the response is successful
        if ($response->successful()) {
            return $response->json();  // Return the JSON response with Aftermarket data
        }

        // Step 6: If the response is not successful, return an error
        return [
            'error' => 'Failed to fetch data',
            'status' => $response->status(),
            'message' => $response->body(),
        ];
    }




}
