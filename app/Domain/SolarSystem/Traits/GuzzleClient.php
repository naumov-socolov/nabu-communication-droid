<?php

namespace App\Domain\SolarSystem\Traits;

use GuzzleHttp\Client;

trait guzzleClient
{
    public $client;

    /**
     * @param string $basePath
     * @return Client
     */
    public function initClient(string $basePath)
    {
        return $this->client = new Client(['base_uri' => $basePath]);
    }
}
