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


use APISIX\Runner\Http\Protocol;
use APISIX\Runner\Handler\HandlerInterface;
use APISIX\Runner\Handler\PrepareConfigHandler;
use APISIX\Runner\Handler\RequestCallHandler;
use APISIX\Runner\Handler\UnknownHandler;

class Handler
{
    /**
     * @var HandlerInterface
     */
    private static $instance;

    /**
     * @param $type
     * @param $buffer
     * @return HandlerInterface
     */
    public static function init($type, $buffer): HandlerInterface
    {
        switch ($type) {
            case Protocol::RPC_TYPE_PREPARE_CONF:
                self::$instance = new PrepareConfigHandler($buffer);
                break;
            case Protocol::RPC_TYPE_HTTP_REQ_CALL:
                self::$instance = new RequestCallHandler($buffer);
                break;
            default:
                self::$instance = new UnknownHandler($buffer);
                break;
        }
        return self::$instance;
    }
}
