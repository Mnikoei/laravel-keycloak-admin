<?php

namespace Mnikoei;

use Mnikoei\Auth\ClientAuthService;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Http\Request;
use Mnikoei\Services\Client;
use Mnikoei\Services\ClientRole;
use Mnikoei\Services\Role;
use Mnikoei\Services\User;

class AdminService
{
    protected $container = [];

    function __construct(ClientAuthService $auth) {

        $this->auth = $auth;
        $this->registerServices();
    }

    public function getService(string $service)
    {
          return new $this->container[$service]($this->auth , new HttpClient());
    }

    public function registerServices() : void
    {
        $this->container =[
           'User' => User::class,
           'Role' => Role::class,
           'Client' => Client::class,
           'ClientRole' => ClientRole::class,
        ];
    }

    public function user()
    {
       return $this->getService('User');
    }

    public function role()
    {
        return $this->getService('Role');
    }

    public function client()
    {
        return $this->getService('Client');
    }

    public function clientRole()
    {
        return $this->getService('ClientRole');
    }
}
