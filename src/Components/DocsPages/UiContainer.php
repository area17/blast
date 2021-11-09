<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use A17\Blast\UiDocsStore;

class UiContainer extends Component
{
    /** @var string */
    public $type;

    /** @var array */
    public $items;

    public function __construct(UiDocsStore $uiDocsStore)
    {
        $this->uiDocsStore = $uiDocsStore;
        $this->type = 'text-list';
        $this->items = $this->uiDocsStore->get('theme.mainColWidths') ?? null;
    }

    public function render(): View
    {
        return view('blast::components.ui-docs.layout');
    }
}
