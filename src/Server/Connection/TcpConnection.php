<?php

declare(strict_types=1);
/**
 * happy coding.
 */
namespace Kuang\Kdis\Server\Connection;

use Kuang\Kdis\EventLoop\Factory;
use Kuang\Kdis\EventLoop\LoopInterface;

class TcpConnection implements ConnectionInterface, ReadAbleInterface, WriteAbleInterface
{
    protected $stream;

    protected LoopInterface $loop;

    protected string $readBuffer = '';

    protected string $writeBuffer = '';

    public function __construct($stream)
    {
        $this->stream = $stream;
        $this->loop = Factory::getInstance();
    }

    public function handle()
    {
        $this->loop->addReadStream($this->stream, function ($stream) {
            var_dump('handle->addReadStream->read');
            $this->read($stream);
        });
    }

    public function read($stream)
    {
        var_dump(__METHOD__);
        $this->readBuffer = '';
        if (is_resource($stream)) {
            while ($content = fread($stream, 65535)) {
                $this->readBuffer .= $content;
            }
        }
        var_dump('client:' . (int) $stream . '=>' . $this->readBuffer);
        if (strlen($this->readBuffer) <= 0) {
        }
        if ($this->readBuffer && strlen($this->readBuffer) > 0) {
            $this->write($stream, $this->getRandStr());
//            $this->loop->addWriteStream($stream, function ($stream) {
//                $this->write($stream, $this->getRandStr());
//            });
        } else {
            var_dump('removeReadStream');
            $this->loop->removeReadStream($stream);
            fclose($stream);
        }
    }

    public function write($stream, string $message = '')
    {
        if (is_resource($stream)) {
            var_dump(__METHOD__ . ' fwriting!');
            $ret = fwrite($stream, $this->encode($message));
            var_dump(__METHOD__ . ' fwriting!ret=' . $ret);
        } else {
            var_dump(__METHOD__ . ' stream is not resource!');
        }
    }

    public function encode(string $message): string
    {
        $len = strlen($message);
        return "HTTP/1.1 200 OK\r\nContent-Type: text/html;charset=utf8\r\nContent-Length:{$len}\r\nConnection: keep-alive\r\n\r\n{$message}\r\n";
    }

    public function getRandStr(): string
    {
        $str = 'abcdefhkjdsidskvfkoe2548914@!#324323415';
        $bit = mt_rand(10, 100);
        $result = '';
        $_bit = $bit;
        $len = strlen($str);
        while ($_bit-- > 0) {
            $result .= $str[mt_rand(0, $len - 1)];
        }
        return $result;
    }
}
