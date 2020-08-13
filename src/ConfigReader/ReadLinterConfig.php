<?php

namespace Spacetab\Linter\ConfigReader;

use Spacetab\Linter\Exception\LinterException;
use Symfony\Component\Yaml\Yaml;

class ReadLinterConfig
{

    const DEFAULT_PATH = __DIR__ . '/../../.st-linter.yaml';

    private string $configurationFilePath;

    public function __construct(?string $configurationFilePath)
    {
        $this->configurationFilePath = self::DEFAULT_PATH;
        if ($configurationFilePath) {
            $this->configurationFilePath = $configurationFilePath;
        }
    }

    /**
     * @return LinterConfig
     * @throws LinterException
     */
    public function load(): LinterConfig
    {
        $linterConfig = new LinterConfig();
        $content = Yaml::parseFile($this->configurationFilePath)['parameters'];

        if ( ! array_key_exists('stage', $content)) {
            throw LinterException::linterConfigurationException('stage');
        }
        $linterConfig->setStage($content['stage']);

        if ( ! array_key_exists('configurationsPath', $content)) {
            throw LinterException::linterConfigurationException('configurationsPath');
        }

        $linterConfig->setConfigurationPath($content['configurationsPath']);

        if ( ! array_key_exists('rules', $content)) {
            throw LinterException::linterConfigurationException('rules');
        }
        $linterConfig->setRules($content['rules']);

        return $linterConfig;
    }
}