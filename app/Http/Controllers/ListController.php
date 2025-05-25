<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ListController extends Controller
{
    public function getListGroups(Request $request){
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
        $finalArray = [];
        foreach ($allGroups as $groupName => $groupLinks) {
            foreach ($groupLinks as $groupLink) {
                if ($groupLink['action'] == 'getGroupParts') {
                    $data = $this->getGroupParts($groupLink['token']);
                    if (!empty($data['data'])){
                        $finalArray[$groupName] = $data['data'];
                    }
                }
            }
        }
    }


    private function getGroupParts($token)
    {
        $groupPartsData = [];
        $payload = [
            //token koji smo dobili od getGroups > data > children > odredjena grupa delova koja nam treba Index niz > links > getGroupParts
            'token' => $token,
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
//        $groupPartsData = [];
        if (!empty($data['data'])) {
            $groupPartsData[$data['data']['categories'][0]['category']['name']] = [];
            foreach ($data['data']['categories'][0]['units'] as $unit) {
                $groupPartsData[$data['data']['categories'][0]['category']['name']] = [
                  $unit['unit']['name'] => []
                ];
                $parts = $this->getUnitPart($unit['unit']['links'][1]['token']);
                foreach ($parts as $part) {
                    $groupPartsData[$data['data']['categories'][0]['category']['name']][ $unit['unit']['name']][] = $part['partName'];
                }
            }
            return $groupPartsData;
        }
        return [];
    }


    private function getUnitPart($token)
    {
        $payload = [
            'token' => $token,
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

        if (isset($response->json()['data']['partSections'][0]['parts'])){
            return $response->json()['data']['partSections'][0]['parts'];
        }
        else {
            return [];
        }
    }
}
