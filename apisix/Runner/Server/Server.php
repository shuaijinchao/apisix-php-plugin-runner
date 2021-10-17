<?php

namespace APISIX\Runner\Server;

class Server
{

    /**
     * @var Config
     */
    protected $config;

    protected $sock;

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
                die("ERR: socket_accept failed, reason: " . socket_strerror(socket_last_error($this->sock)));
            }
            // reading protocol header
            $buffer = socket_read($conn, 4);
            if (!$buffer) {
                continue;
            }
            $bytes = unpack("Sty/nlength", $buffer);
            dump($bytes["ty"]);
            dump($bytes["length"]);
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
