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

namespace APISIX\Runner\Server;


class Config
{

    const DEFAULT_SOCKET_ADDRESS = "/tmp/runner.sock";

    const DEFAULT_DEBUG_MODE = false;

    private $debug = Config::DEFAULT_DEBUG_MODE;

    private $socketAddress = Config::DEFAULT_SOCKET_ADDRESS;

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     */
    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    /**
     * @return string
     */
    public function getSocketAddress(): string
    {
        return $this->socketAddress;
    }

    /**
     * @param string $socketAddress
     */
    public function setSocketAddress(string $socketAddress): void
    {
        if ($socketAddress) {
            $this->socketAddress = str_replace("unix:", "", $socketAddress);
        }
    }
}
