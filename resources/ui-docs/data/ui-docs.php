<?php

use A17\Blast\UiDocsStore;

$uiDocsStore = new UiDocsStore();

$transitionDuration = $uiDocsStore->get('theme.transitionDuration');
$transitionDelay = $uiDocsStore->get('theme.transitionDelay');

$transitionDelay->prepend('0ms', 0);

return [
    'transitions' => [
        'args' => [
            'duration' => $transitionDuration->first(),
            'delay' => $transitionDelay->first(),
        ],
        'argTypes' => [
            'duration' => [
                'control' => 'select',
                'options' => $transitionDuration->values(),
            ],
            'delay' => [
                'control' => 'select',
                'options' => $transitionDelay->values(),
            ],
        ],
    ],
];
