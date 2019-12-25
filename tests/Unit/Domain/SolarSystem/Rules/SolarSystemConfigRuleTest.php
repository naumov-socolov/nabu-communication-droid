<?php

namespace Tests\Unit\Domain\SolarSystem\Rules;

use App\Domain\SolarSystem\Models\SolarSystem;
use App\Domain\SolarSystem\Rules\SolarSystemConfig as SolarSystemConfigRule;
use Tests\TestCase;

class SolarSystemConfigRuleTest extends TestCase
{
    public function test_solar_system_driver_must_be_configured()
    {
        $rule = new SolarSystemConfigRule();

        $eriaduSolarSystem = factory(SolarSystem::class)->create(['driver_name' => 'eriadu']);
        $this->assertTrue($rule->passes('', $eriaduSolarSystem));

        $dantooineSolarSystem = factory(SolarSystem::class)->create(['driver_name' => 'dantooine']);
        $this->assertFalse($rule->passes('', $dantooineSolarSystem));
    }
}
