<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class GenerateStoriesTest extends TestCase
{
    public function test_can_generate_all_components()
    {
        $this->copyStubs([
            'link.blade.php' => resource_path('views/components/link/link.blade.php'),
            'link.story.blade.php' => resource_path('views/stories/link/link.blade.php'),
            'paragraph.blade.php' => resource_path('views/components/paragraph/paragraph.blade.php'),
            'paragraph.story.blade.php' => resource_path('views/stories/paragraph/paragraph.blade.php'),
        ]);

        $linkStories = $this->vendorPath('stories/link.stories.json');
        $paragraphStories = $this->vendorPath('stories/paragraph.stories.json');

        $this->assertFalse(File::exists($linkStories));
        $this->assertFalse(File::exists($paragraphStories));

        Artisan::call('blast:generate-stories');

        $this->assertTrue(File::exists($linkStories));
        $this->assertTrue(File::exists($paragraphStories));
    }

    public function test_can_generate_one_component()
    {
        $this->copyStubs([
            'link.blade.php' => resource_path('views/components/link/link.blade.php'),
            'link.story.blade.php' => resource_path('views/stories/link/link.blade.php'),
            'paragraph.blade.php' => resource_path('views/components/paragraph/paragraph.blade.php'),
            'paragraph.story.blade.php' => resource_path('views/stories/paragraph/paragraph.blade.php'),
        ]);

        $linkStories = $this->vendorPath('stories/link.stories.json');
        $paragraphStories = $this->vendorPath('stories/paragraph.stories.json');

        $this->assertFalse(File::exists($linkStories));
        $this->assertFalse(File::exists($paragraphStories));

        Artisan::call('blast:generate-stories link');

        $this->assertTrue(File::exists($linkStories));
        $this->assertFalse(File::exists($paragraphStories));
    }
}
