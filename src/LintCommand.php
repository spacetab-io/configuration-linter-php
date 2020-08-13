<?php

namespace Spacetab\Linter;

use Spacetab\Linter\ConfigReader\ReadLinterConfig;
use Spacetab\Linter\OutputPresenter\ArrayToConsolePresenter;
use Spacetab\Linter\Rules\CamelCaseStyleRule;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Spacetab\Configuration\Exception\ConfigurationException;

class LintCommand extends Command
{

    public function configure()
    {
        $this->setName('st-linter')
             ->setDescription("")
             ->addArgument('configurationFilePath', InputArgument::OPTIONAL, 'Configuration file path')
             ->addArgument('rulesArray', InputArgument::IS_ARRAY, 'Array of rules with values');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws ConfigurationException
     * @throws Exception\LinterException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $linter                    = new Configuration();
        $linterConfigurationReader = new ReadLinterConfig($input->getArgument('configurationFilePath'));
        $linter->setLinterConfiguration($linterConfigurationReader->load());

        $linter->addRule(new CamelCaseStyleRule());
        $linter->setOutputPresenter(new ArrayToConsolePresenter());
        $linter->run();

        return 0;
    }
}