<?php

declare(strict_types=1);
/**
 * happy coding.
 */
namespace Kuang\Kdis\EventLoop;

final class StreamSelectLoop implements LoopInterface
{
    private bool $running;

    private array $readStrems = [];

    private array $readListeners = [];

    private array $writeStrems = [];

    private array $writeListeners = [];

    public function addReadStream($stream, callable $listener)
    {
        $key = (int) $stream;
        if (! isset($this->readStrems[$key])) {
            $this->readStrems[$key] = $stream;
            $this->readListeners[$key] = $listener;
        }
    }

    public function addWriteStream($stream, callable $listener)
    {
        $key = (int) $stream;
        if (! isset($this->writeStrems[$key])) {
            $this->writeStrems[$key] = $stream;
            $this->writeListeners[$key] = $listener;
        }
    }

    public function removeReadStream($stream)
    {
        $key = (int) $stream;
        unset($this->readStrems[$key],$this->readListeners[$key]);
    }

    public function removeWriteStream($stream)
    {
        $key = (int) $stream;
        unset($this->writeStrems[$key],$this->writeListeners[$key]);
    }

    public function run()
    {
        $this->running = true;
        while ($this->running) {
            $timeout = 3;
            $this->waitForStreamActivity($timeout);
        }
    }

    public function stop()
    {
        $this->running = false;
    }

    private function waitForStreamActivity($timeout)
    {
        $read = $this->readStrems;
        $write = $this->writeStrems;

        $available = $this->streamSelect($read, $write, $timeout);
        if ($available === false) {
            return;
        }

        foreach ($read as $stream) {
            $key = (int) $stream;
            if (isset($this->readListeners[$key])) {
                \call_user_func($this->readListeners[$key], $stream);
            }
        }

        foreach ($write as $stream) {
            $key = (int) $stream;
            if (isset($this->writeListeners[$key])) {
                \call_user_func($this->writeListeners[$key], $stream);
            }
        }
    }

    private function streamSelect(&$read, &$write, $timeout)
    {
        if ($read || $write) {
            $except = null;
            if (\DIRECTORY_SEPARATOR === '\\') {
                $except = [];
                foreach ($write as $key => $socket) {
                    if (! isset($read[$key]) && @\ftell($socket) === 0) {
                        $except[$key] = $socket;
                    }
                }
            }
            $ret = @\stream_select($read, $write, $except, 0, $timeout ?: 0);
            if ($except) {
                $write = \array_merge($write, $except);
            }
            return $ret;
        }

        if ($timeout > 0) {
            \usleep($timeout);
        } elseif ($timeout === null) {
            \sleep(PHP_INT_MAX);
        }
        return false;
    }
}
