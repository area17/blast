<?php

namespace A17\Blast\Facades;

use Illuminate\Support\Facades\Facade;

class DataStore extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'blast.datastore';
    }
}
