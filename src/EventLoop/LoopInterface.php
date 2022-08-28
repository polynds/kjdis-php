<?php

declare(strict_types=1);
/**
 * happy coding.
 */
namespace Kuang\Kdis\EventLoop;

interface LoopInterface
{
    public function addReadStream($stream, callable $listener);

    public function addWriteStream($stream, callable $listener);

    public function removeReadStream($stream);

    public function removeWriteStream($stream);

    public function run();

    public function stop();
}
