<?php

declare(strict_types=1);
/**
 * happy coding.
 */
set_time_limit(0);

function getChar(): string
{
    echo '请输入：';
    return trim(fgets(STDIN));
}

function send($socket, string $content)
{
    return fwrite($socket, $content . '\r\n');
}

function close($socket)
{
    return send($socket, '');
}

function read($socket): string
{
    echo "reading...\n";
    $buffer = '';
    while ($content = fread($socket, 65535)) {
        $buffer .= $content;
    }
//    while ($socket && feof($socket) !== false) {
//        $buffer .= fread($socket, 2048);
//        echo "dsadasas\n";
//    }
    return $buffer;
}

$socket = stream_socket_client('tcp://127.0.0.1:8080', $errno, $errstr);
if (! $socket) {
    echo "{$errstr} ({$errno})<br />\n";
    return;
}
stream_set_blocking($socket, false);

while (true) {
    $cmd = getChar();
    if ($cmd == 'x') {
        close($socket);
        break;
    }
    send($socket, $cmd);
    var_dump(read($socket));
}
fclose($socket);
