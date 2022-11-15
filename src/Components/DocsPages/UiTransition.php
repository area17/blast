<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use A17\Blast\UiDocsStore;

class UiTransition extends Component
{
    /** @var string */
    public $type;

    /** @var array */
    public $items;

    /** @var string */
    public $prefix;

    /** @var string */
    public $property;

    /** @var string */
    public $duration;

    /** @var string */
    public $delay;

    public function __construct(
        UiDocsStore $uiDocsStore,
        $duration = null,
        $delay = null
    ) {
        $this->uiDocsStore = $uiDocsStore;
        $this->duration = $duration;
        $this->delay = $delay;
        $this->prefix = 'ease';
        $this->items = $this->uiDocsStore->get(
            'theme.transitionTimingFunction',
        );
    }

    public function render(): View
    {
        return view('blast::components.ui-docs.transition');
    }
}
