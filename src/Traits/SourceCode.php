<?php

namespace A17\Blast\Traits;

trait SourceCode
{
    private function fileContents($filepath)
    {
        return file_get_contents($filepath);
    }
}