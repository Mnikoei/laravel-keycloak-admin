<?php

namespace Mnikoei\Services;

use Mnikoei\Auth\ClientAuthService;
use Mnikoei\Services\Traits\HasApi;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Arr;


class ClientRole
{

    use HasApi;

    /*
     * Api uri's
     */
    protected $api = [];

    /*
     * Http client
     */
    protected $http;

    /*
     * Client authorization service
     */
    protected $auth;


    function __construct(ClientAuthService $auth , ClientInterface $http) {

        $this->auth = $auth;
        $this->http = $http;
        $this->api = config('keycloakAdmin.api.client_roles');

    }


    public function __call($api , $args)
    {

        $args = Arr::collapse($args);

        list($url , $method) = $this->getApi($api , $args);

        $response = $this
            ->http
            ->request($method , $url, $this->createOptions($args));

        return $this->response($response);

    }



    /**
     * Creates guzzle http clinet options
     * @param array|null $params
     * @return array
     */

    public function createOptions(array $params = null) : array
    {
        return  [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$this->auth->getToken()
            ],
            'json' => $params['body'] ?? null,
        ];
    }


    /**
     * return appropriate response
     */

    public function response($response)
    {
        if (!empty( $location = $response->getHeader('location') )){

            $url = current($location) ;

            return $this->getByName([
                'role' => substr( $url , strrpos( $url , '/') + 1 )
            ]);
        }

        return json_decode($response->getBody()->getContents() , true) ?: true ;
    }


}
