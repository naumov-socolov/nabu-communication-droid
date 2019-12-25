<?php

namespace App\Domain\SolarSystem\Rules;

use Illuminate\Contracts\Validation\Rule;

class SolarSystemConfig implements Rule
{
    public function passes($attribute, $value)
    {
        return config('solar_systems.' . $value->driver_name) !== null;
    }

    public function message()
    {
        return 'Requested Solar System communication doctrine is not signed by Galactic Merchant Consortium';
    }
}
