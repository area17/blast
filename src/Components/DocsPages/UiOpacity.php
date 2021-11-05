<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use A17\Blast\UiDocsStore;

class UiOpacity extends Component
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
        $this->prefix = 'opacity';
        $this->property = 'opacity';
        $this->items = $this->uiDocsStore->get('theme.opacity');
    }

    public function render(): View
    {
        return view('blast::components.ui-docs.grid');
    }
}
