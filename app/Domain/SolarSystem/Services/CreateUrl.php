<?php

namespace App\Domain\SolarSystem\Services;

use App\Domain\SolarSystem\Models\SolarSystem;

class CreateUrl
{
    const HTTP = 'http://';

    /**
     * @param string      $url
     * @param SolarSystem $solarSystem
     * @return string
     */
    public function handle(string $url, SolarSystem $solarSystem): string
    {
        if ($this->isOuterRimGalaxy($solarSystem)) {
            return self::HTTP . $url;
        }

        return $url;
    }

    /**
     * @param SolarSystem $solarSystem
     * @return bool
     */
    private function isOuterRimGalaxy(SolarSystem $solarSystem)
    {
        return true;
    }
}
