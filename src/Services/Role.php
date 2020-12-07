<?php

namespace Mnikoei\Services;

use Mnikoei\Auth\ClientAuthService;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Arr;

class Role extends Service
{
    /**
     * Role constructor.
     * @param ClientAuthService $auth
     * @param ClientInterface $http
     */
    public function __construct(ClientAuthService $auth , ClientInterface $http)
    {
        parent::__construct($auth, $http);
        $this->api = config('keycloakAdmin.api.role');
    }

    /**
     * @param $response
     * @return bool
     */
    public function response($response)
    {
        if (! empty($location = $response->getHeader('location'))){

            $url = current($location) ;

            return $this->getByName([
                'role' => substr($url,strrpos($url, '/') + 1)
            ]);
        }

        return json_decode($response->getBody()->getContents(), true) ?: true;
    }
}
