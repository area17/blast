<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class DocsPage extends Component
{
    /** @var string */
    public $label;

    /** @var string */
    public $title;

    /** @var string */
    public $description;

    public function __construct(
        $label = null,
        $title = null,
        $description = null,
    ) {
        $this->label = $label;
        $this->title = $title;
        $this->description = $description;
    }

    public function render(): View
    {
        return view('blast::components.docs-page');
    }
}
