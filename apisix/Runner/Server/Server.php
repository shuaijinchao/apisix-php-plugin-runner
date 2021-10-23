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

class Server
{
    /**
     * @var Config
     */
    protected $config;

    protected $sock;

    /**
     * Server constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function listen()
    {
        if (file_exists($this->config->getSocketAddress())) {
            unlink($this->config->getSocketAddress());
        }
        $this->sock = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if (!$this->sock) {
            die("ERR: socket_create() failed, reason: " . socket_strerror(socket_last_error()));
        }

        if (!socket_bind($this->sock, $this->config->getSocketAddress())) {
            die("ERR: socket_bind() failed, reason: " . socket_strerror(socket_last_error($this->sock)));
        }

        if (!socket_listen($this->sock, 1024)) {
            die("ERR: socket_listen failed, reason: " . socket_strerror(socket_last_error($this->sock)));
        } else {
            chmod($this->config->getSocketAddress(), 0777);
            print("listening on unix:" . $this->config->getSocketAddress() . "\n");
        }

        $this->accept();
    }

    private function accept()
    {
        do {
            $conn = socket_accept($this->sock);
            if (!is_resource($conn)) {
                die("ERR: socket_accept failed, reason: " . socket_strerror(socket_last_error($conn)));
            }

            // reading request header
            $buffer = socket_read($conn, 4);
            if (!$buffer) {
                continue;
            }

            // parsing request header
            $bytes = unpack("sty/nlen", $buffer);
            print("request type: " . $bytes["ty"] . " len: " . $bytes["length"] . "\n");

            // reading request data
            $buffer = socket_read($conn, $bytes["len"]);
            if (!$buffer) {
                print("ERR: socket_read failed, reason: " . socket_strerror(socket_last_error($conn)) . "\n");
                continue;
            }

            // processing request
            $handler = Handler::init($bytes["ty"], $buffer);
            $handler->dispatch();
        } while (true);
    }

    public function __destruct()
    {
        if ($this->sock) {
            socket_close($this->sock);
            print("close on unix:" . $this->config->getSocketAddress() . "\n");
        }
        if (file_exists($this->config->getSocketAddress())) {
            unlink($this->config->getSocketAddress());
            print("remove " . $this->config->getSocketAddress() . " socket file handler\n");
        }
    }
}
