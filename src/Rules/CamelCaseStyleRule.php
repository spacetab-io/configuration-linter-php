<?php

namespace Spacetab\Linter\Rules;

use Jawira\CaseConverter\Convert;
use Jawira\CaseConverter\CaseConverterException;

class CamelCaseStyleRule implements RuleInterface
{

    /**
     * @param array $map
     *
     * @return array
     * @throws CaseConverterException
     */
    public function check(array $map): array
    {
//        var_dump($map);
        $errors = [];
        foreach ($map as $key => $value) {
            $toConvert = new Convert($value);
//            echo $key  . "\t" . $toConvert->toCamel()  . "\t" . $toConvert->toUpper()  . "\t" . $toConvert->toLower()  . "\n";
            if ($toConvert->toCamel() !== $value && $toConvert->toUpper() !== $value
                && $toConvert->toLower() !== $value && !is_int($value)) {
                $errors[] = $key . " is not in CamelCase\n";
            }
        }

        return $errors;
    }
}