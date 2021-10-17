<?php


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
        $this->socketAddress = $socketAddress;
    }
}
