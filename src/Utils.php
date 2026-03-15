<?php
namespace PekLaiho\PkgAudit;

class Utils
{
    // Write error to stderr. Exit, unless $status < 0
    public static function error(string $message, int $status = 1): void
    {
        fwrite(STDERR, $message . PHP_EOL);

        if ($status >= 0) {
            exit($status);
        }
    }

    // Write to stdout
    public static function out(string $message): void
    {
        fwrite(STDOUT, $message);
    }

    // Write to stdout and add linebreak
    public static function outln(string $message): void
    {
        self::out($message . PHP_EOL);
    }
}
