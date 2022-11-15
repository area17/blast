<?php

namespace A17\Blast\Traits;

/**
 * @deprecated Calling static methods on a trait is deprecated as of PHP 8.1. Use \A17\Blast\Storybook\StorybookLogo::defaultLogo() instead
 * 
 * @link https://www.php.net/manual/en/migration81.deprecated.php#migration81.deprecated.core.static-trait
 * @see \A17\Blast\Storybook\StorybookLogo::defaultLogo()
 */
trait StorybookLogo
{
    public static function defaultLogo()
    {
       return \A17\Blast\Storybook\StorybookLogo::defaultLogo();
    }
}
