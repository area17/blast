<?php

namespace A17\Blast\Support;

class Transformer
{
    public function compileTransformer($code)
    {
        $code = trim($code);

        if (class_exists($code)) {
            return '
                <?php
                    foreach('.$code.'::blast("'.$code.'", get_defined_vars()) as $var => $value) {
                        $$var = $value;
                    }
                ?>
            ';
        }

        return $code;
    }

    public static function transform($transformerClass, $bladeDefinedVars): array
    {
        $transformer = new $transformerClass();

        $bladeDefinedVars['__blast'] = $transformer->setData($bladeDefinedVars['__data'])->transform();

        if (
            blank($bladeDefinedVars['__blast']) &&
            method_exists($transformer, 'transformStorybookData')
        ) {
            $bladeDefinedVars['__blast'] = $transformer->transformStorybookData() ?? [];
        }

        return $bladeDefinedVars['__blast'];
    }
}
