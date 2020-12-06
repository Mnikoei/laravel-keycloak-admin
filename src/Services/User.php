<?php

namespace Mnikoei\Services;

use Mnikoei\Auth\ClientAuthService;
use Mnikoei\Services\Traits\HasApi;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Arr;

class User extends Service
{
    public function __construct(ClientAuthService $auth , ClientInterface $http)
    {
        parent::__construct($auth, $http);
        $this->api = config('keycloakAdmin.api.user');
    }

    public function response($response)
    {
        if (!empty( $location = $response->getHeader('location') )){

            $url = current($location) ;

            return $this->get([
                'id' => substr( $url , strrpos( $url , '/') + 1 )
            ]);
        }

        return json_decode($response->getBody()->getContents() , true) ?: true ;
    }
}
