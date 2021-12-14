<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use A17\Blast\UiDocsStore;

class UiMinHeight extends Component
{
    /** @var string */
    public $type;

    /** @var array */
    public $items;

    /** @var string */
    public $prefix;

    /** @var string */
    public $property;

    public function __construct(UiDocsStore $uiDocsStore)
    {
        $this->uiDocsStore = $uiDocsStore;
        $this->prefix = 'min-h';
        $this->property = 'height';
        $this->items = $this->uiDocsStore->get('theme.minHeight');
    }

    public function render(): View
    {
        return view('blast::components.ui-docs.full-width');
    }
}
