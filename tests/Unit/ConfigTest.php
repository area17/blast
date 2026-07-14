<?php

namespace A17\Blast\Tests\Unit;

use Tests\TestCase;

class ConfigTest extends TestCase
{
    public function test_it_loads_default_bind_host_for_windows_safety()
    {
        $this->assertEquals('127.0.0.1', config('blast.storybook_bind_host'));
    }

    public function test_it_can_override_bind_host_via_environment_for_docker()
    {
        config(['blast.storybook_bind_host' => '0.0.0.0']);

        $this->assertEquals('0.0.0.0', config('blast.storybook_bind_host'));
    }
}
