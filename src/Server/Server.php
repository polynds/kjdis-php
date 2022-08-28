<?php

declare(strict_types=1);
/**
 * happy coding.
 */
namespace Kuang\Kdis\Server;

use Kuang\Kdis\EventLoop\Factory;
use Kuang\Kdis\EventLoop\LoopInterface;
use Kuang\Kdis\Server\Connection\TcpConnection;
use Kuang\Kdis\Server\Exception\ServerSocketException;

class Server
{
    protected string $ipAddress;

    protected int $port;

    protected $serverSocket;

    protected int $errno = 0;

    protected string $errMsg = '';

    protected LoopInterface $loop;

    public function __construct(string $ipAddress, int $port)
    {
        if (! filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new ServerSocketException('ip address illegal');
        }
        if (! filter_var($port, FILTER_VALIDATE_INT) || ($port > 65535 || $port <= 0)) {
            throw new ServerSocketException('port illegal');
        }
        $this->ipAddress = $ipAddress;
        $this->port = $port;
        $this->serverSocket = stream_socket_server(sprintf('tcp://%s:%d', $ipAddress, $port), $this->errno, $this->errMsg);
        if (! $this->serverSocket) {
            echo "{$this->errMsg} ({$this->errno})<br />\n";
            throw new ServerSocketException("{$this->errMsg} ({$this->errno})<br />\n");
        }
        stream_set_blocking($this->serverSocket, false);
        $this->loop = Factory::getInstance();
    }

    public function __destruct()
    {
        $this->loop->stop();
        unset($this->loop);
        fclose($this->serverSocket);
    }

    public function run()
    {
        $this->loop->addReadStream($this->serverSocket, function ($stream) {
            $conn = stream_socket_accept($stream);
            stream_set_blocking($conn, false);
            (new TcpConnection($conn))->handle();
        });
        echo "server starting...\n";
        $this->loop->run();
    }
}
