<?php

declare(strict_types=1);
/**
 * happy coding.
 */
namespace Kuang\Kdis\Utils;

class Str
{
    /**
     * 字符串转Byte数组.
     */
    public static function toBytesArray(string $str): array
    {
        $bytes = [];
        for ($i = 0; $i < strlen($str); ++$i) {
            $bytes[] = ord($str[$i]);
        }
        return $bytes;
    }

    /**
     * Byte数组转字符串.
     * @param mixed $bytes
     */
    public function bytesToStr($bytes): string
    {
        $str = '';
        foreach ($bytes as $ch) {
            $str .= chr($ch);
        }
        return $str;
    }
}
