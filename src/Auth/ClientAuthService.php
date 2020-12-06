<?php

namespace Mnikoei\Auth;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class ClientAuthService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->getAuthorizationToken()['access_token'];
    }

    /**
     * @return array
     */
    public function getAuthorizationToken() : array
    {
        $api = config('keycloakAdmin.api.client.token');

        if (Cache::has('keycload-admin-credentials')) {
            return Cache::get('keycload-admin-credentials');
        }

        $response = $this->client->post($api, $this->getOptions());

        $credentials = json_decode($response->getBody()->getContents(),true);

        return tap($credentials, function ($credentials) {
            Cache::put('keycload-admin-credentials', $credentials, $credentials['expires_in']);
        });
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $options = [

            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => config('keycloakAdmin.client.id'),
                'client_secret' => config('keycloakAdmin.client.secret') ,
            ]
        ];
    }
}
