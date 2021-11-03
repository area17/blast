<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use A17\Blast\UiDocsStore;

class UiColors extends Component
{
    /** @var string */
    public $type;

    /** @var array */
    public $colors;

    public function __construct(UiDocsStore $uiDocsStore, $type = 'all')
    {
        $this->uiDocsStore = $uiDocsStore;
        $this->type = $type;
        $this->colors = [];

        $this->parseColors();
    }

    public function render(): View
    {
        return view('blast::components.ui-docs.colors');
    }

    private function parseColors()
    {
        $raw = [
            'colors' => $this->uiDocsStore->get('theme.colors'),
            'textColor' => $this->uiDocsStore->get('theme.textColor'),
            'bgColor' => $this->uiDocsStore->get('theme.backgroundColor'),
            'borderColor' => $this->uiDocsStore->get('theme.borderColor'),
        ];

        if ($this->type === 'bg' || $this->type === 'background') {
            $this->buildClasses($raw['bgColor'], 'bg');
        } elseif ($this->type === 'text') {
            $this->buildClasses($raw['textColor'], 'text');
        } elseif ($this->type === 'border') {
            $this->buildClasses($raw['borderColor'], 'border');
        } else {
            $this->buildClasses($raw['bgColor'], 'bg');
            $this->buildClasses($raw['textColor'], 'text');
            $this->buildClasses($raw['borderColor'], 'border');
        }
    }

    private function buildClasses($data, $prefix = false, $parentKey = false)
    {
        if (!$data) {
            return 1;
        }

        foreach ($data as $key => $value) {
            $newKey = $parentKey ? $parentKey . '-' . $key : $key;

            if (is_array($value)) {
                $this->buildClasses($value, $prefix, $newKey);
            } else {
                $classname = $prefix ? $prefix . '-' . $newKey : $newKey;
                $this->colors[$classname] = [
                    'type' => $prefix,
                    'color' => $value,
                ];
            }
        }
    }
}
