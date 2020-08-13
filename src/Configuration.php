<?php

namespace Spacetab\Linter;

use Spacetab\Configuration\Exception\ConfigurationException;
use Spacetab\Linter\ConfigReader\LinterConfig;
use Spacetab\Linter\ConfigurationLoader\ConfigurationLoader;
use Spacetab\Linter\Rules\RuleInterface;
use Spacetab\Linter\OutputPresenter\PresenterInterface;

class Configuration
{
    /** @var array */
    private array $rules = [];

    /** @var LinterConfig */
    private LinterConfig $linterConfig;

    /** @var PresenterInterface */
    private PresenterInterface $presenter;

    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    public function setLinterConfiguration(LinterConfig $linterConfig)
    {
        $this->linterConfig = $linterConfig;
    }

    public function setOutputPresenter(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * @throws ConfigurationException
     */
    public function run()
    {

        $configurationLoader = new ConfigurationLoader($this->linterConfig->getConfigurationPath());
        $default             = $configurationLoader->loadDefaultConfig();
        $staged              = [];
        if ($this->linterConfig->getStage()) {
            $staged = $configurationLoader->loadStagedConfig($this->linterConfig->getStage());
        }

        $result = [];
        /** @var RuleInterface $rule */
        foreach ($this->rules as $rule) {
            $result = array_merge($result, $rule->check($default));
            $result = array_merge($result, $rule->check($staged));
        }

        $this->presenter->present($result);
    }
}

