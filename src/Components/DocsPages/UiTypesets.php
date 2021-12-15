<?php

namespace A17\Blast\Components\DocsPages;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use A17\Blast\UiDocsStore;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UiTypesets extends Component
{
    /** @var array */
    public $items;

    public $screens;

    public function __construct(UiDocsStore $uiDocsStore)
    {
        $this->uiDocsStore = $uiDocsStore;
        $this->screens = collect(
            $this->uiDocsStore->get('theme.screens'),
        )->keys();
        $this->fontFamilies = collect(
            $this->uiDocsStore->get('theme.fontFamilies'),
        );
        $typesets = collect($this->uiDocsStore->get('theme.typesets'));

        if ($typesets->isNotEmpty()) {
            $this->items = $typesets->map(function ($item) {
                $row = [];

                $index = 0;
                foreach ($item as $breakpoint => $values) {
                    foreach ($values as $property => $value) {
                        $propertyBreakpoints = $this->getPropertyBreakpoints(
                            $item,
                            $property,
                        );
                        $currentBreakpointIndex = array_search(
                            $breakpoint,
                            $propertyBreakpoints,
                        );
                        $nextItem =
                            $propertyBreakpoints[$currentBreakpointIndex + 1] ??
                            false;
                        $span = $this->getSpan(
                            $propertyBreakpoints[$currentBreakpointIndex],
                            $nextItem ?? null,
                        );

                        if (
                            $property === 'font-family' &&
                            Str::startsWith($value, 'var(--')
                        ) {
                            preg_match('/var\(--(.*)\)/sU', $value, $matches);

                            if (filled($matches)) {
                                $value = $this->fontFamilies->get(
                                    $matches[1],
                                    $value,
                                );
                            }
                        }

                        $row[$property][$breakpoint] = [
                            'span' => $span,
                            'value' => $value,
                        ];
                    }
                    $index++;
                }

                return $row;
            });
        }
    }

    private function getPropertyBreakpoints($data, $property)
    {
        $bps = [];

        foreach ($data as $breakpoint => $values) {
            if (Arr::has($values, $property)) {
                $bps[] = $breakpoint;
            }
        }

        return $bps;
    }

    private function getSpan($current, $next)
    {
        $current_index = $this->screens->search($current);
        $next_index = $next
            ? $this->screens->search($next)
            : $this->screens->count();

        return $next_index - $current_index;
    }

    public function bgColor($property = null)
    {
        switch ($property) {
            case 'font-size':
                $color = 'blast-bg-red-100';
                break;

            case 'font-weight':
                $color = 'blast-bg-indigo-100';
                break;

            case 'font-family':
                $color = 'blast-bg-yellow-100';
                break;

            case 'line-height':
                $color = 'blast-bg-blue-100';
                break;

            case 'letter-spacing':
                $color = 'blast-bg-green-100';
                break;

            default:
                $color = 'blast-bg-gray-100';
                break;
        }

        return $color;
    }

    public function render(): View
    {
        return view('blast::components.ui-docs.typesets');
    }
}
