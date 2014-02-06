<?php namespace Marcanuy\Popularity\Facades;

use Illuminate\Support\Facades\Facade;

class Popularity extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'popularity'; }

}