<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchCategoriesAndParts extends Command
{
    protected $signature = 'yqyq:fetch-all-parts';
    protected $description = 'Fetch all parts from YQService and save to a PHP file';

    public function handle()
    {
        $token = 'AT0teiUtNHIqUFErMXRvdzs4OT44PzEpenobf31_KjUsQ0YMEwFMNz4-Pz47OW5vdjgsJSpQfnp_dGJmWUtBQUxCTEISHQpbXV1aRkBOKXp6Gy00K1xlTH1iZQ0bV2c5KS1zJSkmen50LEppe29lZDF6fi1DSFJLTzs7ZGhmciIrfmZqKzF0IxgtZ21ULTRVKWdsdj49Pzw8PTBXdHQYLX16bFMsM3AKeilufk1pe29VKWwjGC1gbXBTLDNXdGkYLSJVKmp7e2QmPSJ9b2RtUywzV3RoGC0iVSp5Z2dXdGIYLUREUkRLSjJhbnQ_PDswODw-V3R0GC1oe1QtNFUpMARmcnN0KnIAAAAAx7tL-w==';

        $groups = $this->getGroups($token);

        $allGroups = [];
        $this->extractGroups($groups, $allGroups);

        $finalArray = [];
        foreach ($allGroups as $groupName => $groupLinks) {
            foreach ($groupLinks as $link) {
                if ($link['action'] === 'getGroupParts') {
                    $this->info("Fetching parts for: $groupName");
                    $data = $this->getGroupParts($link['token']);
                    if (!empty($data)) {
                        $finalArray[$groupName] = $data;
                    }
                }
            }
        }

        $phpData = '<?php return ' . var_export($finalArray, true) . ';';
        file_put_contents(storage_path('app/final_parts_data.php'), $phpData);
        $this->info('Saved final array to storage/app/final_parts_data.php');
    }

    private function getGroups(string $token): array
    {
        $payload = [
            'token' => $token,
            'formValues' => [['name' => 'IdentString', 'value' => '']]
        ];

        $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
            ->withHeaders([
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post('https://oem-api.yqservice.eu/restApi/v2/getGroups', $payload);

        return $response->json('data.children') ?? [];
    }

    private function extractGroups(array $groups, array &$allGroups)
    {
        foreach ($groups as $group) {
            if (isset($group['links']) && !empty($group['links'])) {
                $allGroups[$group['name']] = $group['links'];
            } elseif (isset($group['children'])) {
                $this->extractGroups($group['children'], $allGroups);
            }
        }
    }

    private function getGroupParts(string $token): array
    {
        $payload = [
            'token' => $token,
            'formValues' => [['name' => 'IdentString', 'value' => '']]
        ];

        $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
            ->withHeaders([
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post('https://oem-api.yqservice.eu/restApi/v2/getGroupParts', $payload);

        $data = $response->json();

        if (!empty($data['data']['categories'][0]['units'])) {
            $categoryName = $data['data']['categories'][0]['category']['name'] ?? 'Unnamed Category';
            $result = [$categoryName => []];

            foreach ($data['data']['categories'][0]['units'] as $unit) {
                $unitName = $unit['unit']['name'];
                $unitParts = $this->getUnitParts($unit['unit']['links'][1]['token']);

                $result[$categoryName][$unitName] = array_column($unitParts, 'partName');
            }

            return $result;
        }

        return [];
    }

    private function getUnitParts(string $token): array
    {
        $payload = [
            'token' => $token,
            'formValues' => [['name' => 'IdentString', 'value' => '']]
        ];

        $response = Http::withBasicAuth(config('website.http_auth_username'), config('website.http_auth_password'))
            ->withHeaders([
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post('https://oem-api.yqservice.eu/restApi/v2/getUnitParts', $payload);

        return $response->json()['data']['partSections'][0]['parts'] ?? [];
    }
}
