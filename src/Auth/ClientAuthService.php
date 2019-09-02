<?php

namespace Mnikoei\Auth;


use GuzzleHttp\Client;

class ClientAuthService
{


    protected $client;


    public function __construct()
    {

        $this->client = new Client();

    }


    public function getToken()
    {

        return
//            session()->get('keycloak.client.auth')['access_token']
//            ??
            $this->getAuthorizationToken()['access_token'];

    }


    public function getAuthorizationToken() : array
    {

        $api = config('keycloakAdmin.api.client.token');

        $response = $this->client->post( $api , $this->getOptions() );

        $this->saveCredentials( $credentials = json_decode( $response->getBody()->getContents() ,true) );

        return $credentials;

    }


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


    /*
     * save client credentials in a session
     */
    public function saveCredentials($credentials)
    {
        /*
         * set session lifetime based on token expire time dynamically
         */
        Config([
            'session.lifetime' => $credentials['expires_in'] / 60
        ]);

        session(['keycloak.client.auth' => $credentials]);

        session()->save();
    }

}
