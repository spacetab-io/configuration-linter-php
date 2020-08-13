<?php

namespace Spacetab\Linter\ConfigReader;

class LinterConfig
{

    /** @var string */
    private string $stage;

    /** @var array */
    private array $rules;

    /** @var string */
    private string $configurationPath;

    /**
     * @return string
     */
    public function getStage(): string
    {
        return $this->stage;
    }

    /**
     * @param string $stage
     */
    public function setStage(string $stage): void
    {
        $this->stage = $stage;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     */
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    /**
     * @return string
     */
    public function getConfigurationPath(): string
    {
        return $this->configurationPath;
    }

    /**
     * @param string $configurationPath
     */
    public function setConfigurationPath(string $configurationPath): void
    {
        $this->configurationPath = $configurationPath;
    }
}