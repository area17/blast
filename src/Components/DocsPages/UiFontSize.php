<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use A17\Blast\UiDocsStore;

class UiFontSize extends Component
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
        $this->prefix = 'font';
        $this->property = 'font-size';
        $this->items = $this->parseData();
    }

    private function parseData()
    {
        return $this->uiDocsStore
            ->get('theme.fontSize')
            ->map(function ($item, $key) {
                return is_array($item) ? $item[0] : $item;
            });
    }

    public function render(): View
    {
        return view('blast::components.ui-docs.typography');
    }
}
