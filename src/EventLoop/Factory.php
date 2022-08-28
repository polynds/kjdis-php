<?php

declare(strict_types=1);
/**
 * happy coding.
 */
namespace Kuang\Kdis\EventLoop;

class Factory
{
    protected static ?LoopInterface $instance = null;

    public static function getInstance(): LoopInterface
    {
        if (is_null(self::$instance)) {
            self::$instance = new StreamSelectLoop();
        }
        return self::$instance;
    }
}
