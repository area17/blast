<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use A17\Blast\UiDocsStore;

class UiShadows extends Component
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
        $this->prefix = 'shadow';
        $this->property = 'box-shadow';
        $this->items = $this->uiDocsStore->get('theme.boxShadow');
    }

    public function render(): View
    {
        return view('blast::components.ui-docs.grid');
    }
}
