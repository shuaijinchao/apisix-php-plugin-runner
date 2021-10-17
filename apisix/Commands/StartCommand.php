<?php

namespace APISIX\Commands;

use APISIX\Runner\Server\Config;
use APISIX\Runner\Server\Server;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StartCommand extends Command
{
    protected static $defaultName = 'start';

    protected static $defaultDescription = "PHP Runner running Apache APISIX";

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {

        $this->setName("start")->setDescription("PHP Runner running Apache APISIX")
            ->addOption("debug", "d", InputOption::VALUE_NONE, "Enable on debug mode");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $enableDebug  = $input->getOption("debug");
        $serverConfig = new Config();
        $serverConfig->setDebug($enableDebug);
        $server = new Server($serverConfig);
        $server->listen();
        return Command::SUCCESS;
    }
}
