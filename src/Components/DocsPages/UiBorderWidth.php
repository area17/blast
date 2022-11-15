<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use A17\Blast\UiDocsStore;

class UiBorderWidth extends Component
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
        $this->prefix = 'border';
        $this->property = 'border-width';
        $this->items = $this->uiDocsStore->get('theme.borderWidth');
    }

    public function render(): View
    {
        return view('blast::components.ui-docs.grid');
    }
}
