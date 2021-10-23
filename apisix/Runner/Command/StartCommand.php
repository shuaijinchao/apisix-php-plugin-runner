<?php

/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 */

namespace APISIX\Command;

use APISIX\Runner\Server\Config;
use APISIX\Runner\Server\Server;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StartCommand extends Command
{
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
        $socketAddress = getenv("APISIX_LISTEN_ADDRESS");
        $serverConfig = new Config();
        $serverConfig->setDebug($enableDebug);
        $serverConfig->setSocketAddress($socketAddress);
        $server = new Server($serverConfig);
        $server->listen();
        return Command::SUCCESS;
    }
}
