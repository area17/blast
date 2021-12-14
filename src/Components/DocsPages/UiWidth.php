<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use A17\Blast\UiDocsStore;

class UiWidth extends Component
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
        $this->prefix = 'w';
        $this->property = 'width';
        $this->items = $this->uiDocsStore->get('theme.width');
    }

    public function render(): View
    {
        return view('blast::components.ui-docs.full-width');
    }
}
