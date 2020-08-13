<?php

namespace Spacetab\Linter\Exception;

use Exception;

class LinterException extends Exception
{

    public function __construct(string $message)
    {
        parent::__construct(sprintf("\n\n%s", $message));
    }

    public static function linterConfigurationException(string $configKey): self
    {
        return new self("Parameter '$configKey' not found in configuration file.");
    }
}