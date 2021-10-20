<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DemoTest extends TestCase
{
    public function test_can_generate_demo()
    {
        $componentsPattern = resource_path('views/components/blast-demo/*.php');
        $storiesPattern = resource_path('views/stories/blast-demo/*.php');

        $this->assertEmpty(File::glob($componentsPattern));
        $this->assertEmpty(File::glob($storiesPattern));

        Artisan::call('blast:demo');

        $this->assertNotEmpty(File::glob($componentsPattern));
        $this->assertNotEmpty(File::glob($storiesPattern));
    }
}
