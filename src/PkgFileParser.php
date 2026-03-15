<?php
namespace PekLaiho\PkgAudit;

class PkgFileParser
{
    public function parse(string ...$files): array
    {
        $result = [];

        foreach ($files as $file) {
            if (!is_readable($file)) {
                Utils::error("File $file is not readable");
            }

            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                $value = trim($line);

                if (!$value || in_array($value, $result) || str_starts_with($line, '#')) {
                    continue;
                }

                $result[] = $value;
            }
        }

        sort($result);

        return $result;
    }
}
