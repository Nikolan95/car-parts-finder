<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FetchYQParts extends Command
{
    protected $signature = 'yq:fetch-parts {vin=JMZKEC97600228727}';
    protected $description = 'Fetch all parts from YQService API for a VIN and save as PHP array file';

    public function handle()
    {
        $vin = 'JMZKEC97600228727';
        $vehicleToken = $this->getVehicleToken($vin);
        if (!$vehicleToken) {
            $this->error('Failed to retrieve vehicle token');
            return;
        }

        $groups = $this->getGroups($vehicleToken);
        $data = [];

        foreach ($groups as $groupName => $links) {
            foreach ($links as $link) {
                if ($link['action'] === 'getGroupParts') {
                    $parts = $this->getGroupParts($link['token']);
                    if (!empty($parts)) {
//                        dd($parts);
                        foreach ($parts['categories'][0]['units'] as $part) {
                            dd($part['unit']);
                            $data[$groupName][] = $part['unit']['name'];
                        }
                        $data[$groupName][] = $parts;
                    }
                }
            }
        }

        // Save to a PHP file
        $phpData = '<?php return ' . var_export($data, true) . ';';
        file_put_contents(storage_path('app/parts_data.php'), $phpData);

        $this->info('Parts data saved to storage/app/parts_data.php');
    }

    private function getVehicleToken($vin)
    {
        $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
            ->post('https://oem-api.yqservice.eu/restApi/v2/findVehicle', [
                'token' => '','formValues' => [['name' => 'IdentString', 'value' => $vin]]
            ]);
        return $response['data']['vehicles'][0]['token'] ?? null;
    }

    private function getGroups($token)
    {
        $payload = [
            'token' => $token,
            'formValues' => [['name' => 'IdentString', 'value' => '']]
        ];

        $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
            ->post('https://oem-api.yqservice.eu/restApi/v2/getGroups', $payload);

        $data = $response->json();
        $groups = [];

        $traverse = function ($nodes) use (&$groups, &$traverse) {
            foreach ($nodes as $node) {
                if (!empty($node['links'])) {
                    $groups[$node['name']] = $node['links'];
                }
                if (!empty($node['children'])) {
                    $traverse($node['children']);
                }
            }
        };

        $traverse($data['data']['children'] ?? []);
        return $groups;
    }

    private function getGroupParts($token)
    {
        $payload = [
            'token' => $token,
            'formValues' => [['name' => 'IdentString', 'value' => '']]
        ];

        $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
            ->post('https://oem-api.yqservice.eu/restApi/v2/getGroupParts', $payload);

        return $response['data'] ?? [];
    }
}
