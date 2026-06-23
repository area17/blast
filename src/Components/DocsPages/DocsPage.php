<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class DocsPage extends Component
{
    public function __construct(
        public ?string $label = null,
        public ?string $title = null,
        public ?string $description = null,
    ) {
    }

    public function render(): View
    {
        return view('blast::components.docs-page');
    }
}
