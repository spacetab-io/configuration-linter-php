<?php

namespace Spacetab\Linter\Rules;

interface RuleInterface
{

    public function check(array $map): array;
}