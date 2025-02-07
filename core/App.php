<?php

namespace Core;
require "Singleton.php";
use Core\Singleton;

class App extends Singleton
{
    private mixed $services = [];

    /**
     * Register a service to the app
     * @param string $serviceKey
     * @param mixed $service
     * @return void
     */
    public function registerService(string $serviceKey, mixed $service): void
    {
        // check for duplicate
        if(isset($this->services[$serviceKey])){
            die("Service already registered");
        }

        $this->services[$serviceKey] = $service;
    }

    /**
     * Get a service instance
     * @param string | null $serviceKey
     * @return mixed
     */
    public function getService(string | null $serviceKey = null): mixed
    {
        // check if service key provided
        if(!isset($serviceKey)){
            return App::getInstance();
        }

        // check if service exists
        if(!$this->isServiceRegistered($serviceKey)){
            die("No registered service with this key!");
        }

        return $this->services[$serviceKey];
    }

    /** Remove an already registered service instance
     * @param string $serviceKey
     * @return void
     */
    public function removeService(string $serviceKey): void
    {
        // check if service exists
        if(!$this->isServiceRegistered($serviceKey)){
            die("No registered service with this key!");
        }

        unset($this->services[$serviceKey]);
    }

    /**
     * Check if a service is registered
     * @param string $serviceKey
     * @return bool
     */
    public function isServiceRegistered(string $serviceKey):bool
    {
        return isset($this->services[$serviceKey]);
    }

    /**
     * @return void
     * Run the application
     */
    public function run(): void
    {
        if(!$this->isServiceRegistered('router')){
            http_response_code(500);
            die("Router not found");
        }
        Route::get('/error', function(){
           return Response::raw(session('app_500_error_data_internal', ""));
        });
        $this->getService('router')->dispatch();
    }
}