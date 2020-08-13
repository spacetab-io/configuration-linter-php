<?php

namespace Spacetab\Linter\ConfigurationLoader;

use Spacetab\Configuration\Exception\ConfigurationException;
use Spacetab\Obelix\Dot;
use Symfony\Component\Yaml\Yaml;
use Spacetab\Configuration\Configuration as ConfigurationReader;

class ConfigurationLoader
{

    /**
     * Default configuration stage.
     */
    private const DEFAULT_STAGE = 'defaults';

    /** @var string */
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return array
     * @throws ConfigurationException
     */
    public function loadDefaultConfig():array {
        return $this->load(self::DEFAULT_STAGE);
    }

    /**
     * @param string $stage
     *
     * @return array
     * @throws ConfigurationException
     */
    public function loadStagedConfig(string $stage):array {
        return $this->load($stage);
    }

    /**
     * @param string|null $stage
     *
     * @return array
     * @throws ConfigurationException
     */
    private function load(string $stage = null): array
    {
        $files  = $this->getFilesPaths($stage);
        $config = $this->getFilesConfig($files, $stage);

//        $this->configurationReader->load();
//        $config = $this->configurationReader->all();

        $map    = new Dot($config);
        $result = [];
        foreach ($map->toArray() as $key => $value) {
            $result = array_merge($result, $this->getMap($result, $map, $value, $key));
        }
        return $result;
    }

    private function getMap(array $result, Dot $map, array $mapArray, string $mapKey)
    {

        foreach ($mapArray as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->getMap($result, $map, $value, $mapKey . '.' . $key));
            } else {
                $result = array_merge($result, $this->getPartMap($map, $mapKey . '.' . $key, $key));
            }
        }

        return $result;
    }

    private function getPartMap(Dot $map, string $mapKey, string $key = null): array
    {
        $partMap = $map->get($mapKey)->getMap();
        $partMap[$mapKey] = $key;
        return $partMap;
    }

    /**
     * @param string $stage
     *
     * @return array
     * @throws ConfigurationException
     */
    private function getFilesPaths(string $stage = self::DEFAULT_STAGE): array
    {
        $configurationReader = new ConfigurationReader($this->path, $stage);
        $pattern             = $configurationReader->getPath() . '/' . $stage . '/*.yaml';
        $filesPaths          = glob($pattern, GLOB_NOSORT | GLOB_ERR);

        if ($filesPaths === false || count($filesPaths) < 1) {
            throw ConfigurationException::filesNotFound($pattern, $configurationReader->getPath(), $stage);
        }

        return $filesPaths;
    }

    /**
     * @param array  $files
     * @param string $stage
     *
     * @return array
     * @throws ConfigurationException
     */
    private function getFilesConfig(array $files, string $stage = self::DEFAULT_STAGE)
    {
        $config = [];
        foreach ($files as $filename) {
            $content   = Yaml::parseFile($filename);
            $directory = basename(pathinfo($filename, PATHINFO_DIRNAME));
            $top       = key($content);

            if ($directory !== $top) {
                throw ConfigurationException::fileNotEqualsCurrentStage($directory, $top, $filename);
            }
            $config[$stage] = $content;
        }

        return $config;
    }
}