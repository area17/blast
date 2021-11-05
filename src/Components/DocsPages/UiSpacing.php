<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use A17\Blast\UiDocsStore;

class UiSpacing extends Component
{
    /** @var string */
    public $type;

    /** @var array */
    public $spacing;

    /** @var string */
    public $prefix;

    /** @var string */
    public $variation;

    public function __construct(
        UiDocsStore $uiDocsStore,
        $type = 'margin',
        $variation = 'all'
    ) {
        $this->uiDocsStore = $uiDocsStore;
        $this->type = $type;
        $this->variation = $variation;
        $this->spacing = $this->uiDocsStore->get('theme.spacing');
        $this->prefix = $this->getPrefix();
    }

    private function getPrefix()
    {
        $output = '';

        switch ($this->type) {
            case 'padding':
                $output = 'p';
                break;

            case 'negative-margin':
                $output = '-m';
                break;

            default:
                $output = 'm';
                break;
        }

        switch ($this->variation) {
            case 'top':
                $output .= 't';
                break;

            case 'bottom':
                $output .= 'b';
                break;

            case 'left':
                $output .= 'l';
                break;

            case 'right':
                $output .= 'r';
                break;

            case 'horizontal':
                $output .= 'x';
                break;

            case 'vertical':
                $output .= 'y';
                break;
        }

        return $output;
    }

    public function render(): View
    {
        return view('blast::components.ui-docs.spacing');
    }
}
