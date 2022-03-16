<?php


namespace Mnikoei\Services;


use GuzzleHttp\ClientInterface;
use Illuminate\Support\Arr;
use Mnikoei\Auth\ClientAuthService;

abstract class Service
{
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

    public function __construct(ClientAuthService $auth , ClientInterface $http)
    {
        $this->auth = $auth;
        $this->http = $http;
    }

    public function __call($api, $args)
    {
        $args = Arr::collapse($args);

        [$url , $method] = $this->getApi($api, $args);

        $response = $this
            ->http
            ->request($method, $url, $this->createOptions($args));

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
                'Authorization' => 'Bearer ' . $this->auth->getToken()
            ],
            'json' => $params['body'] ?? null,
        ];
    }

    public function getApi($apiName, $values)
    {
        return $this->initApi($apiName, $values) ;
    }

    public function initApi($apiName, $values)
    {
        $api = $this->api[$apiName]['api'];

        foreach($values as $name => $value) {
            if (is_string($value)) {
                $api = str_replace('{'.$name.'}', $value, $api);
            }
        }

        if (isset($values['query'])){
            $api .= $api . '?' . http_build_query($values['query']);
        }

        return [$api ,$this->api[$apiName]['method']];
    }
}
