<?php
namespace PekLaiho\PkgAudit;

class Pacman implements IPackageManager
{
    public function __construct(
        protected ?IPackageCache $cache = null
    ) {

    }

    public function getFullPackageInfo(): array
    {
        $installed = $this->getInstalledPackages();

        $result = [];

        foreach ($installed as $name) {
            $result[$name] = $this->getPackageInfo($name);
        }

        return $result;
    }

    public function getInstalledPackages(): array
    {
        $result = (new ShellRunner())->run([
            'pacman', '-Q'
        ]);

        $lines = explode("\n", trim($result->getStdOut()));

        $names = [];

        foreach ($lines as $line) {
            $parts = explode(' ', $line);
            $names[] = $parts[0];
        }

        return $names;
    }

    public function getPackageInfo(string $name): ?PkgInfo
    {
        // Read from cache first
        if ($this->cache) {
            $cacheResult = $this->cache->get($name);
            if ($cacheResult) {
                return $cacheResult;
            }
        }

        $result = (new ShellRunner())->run([
            'pacman', '-Qi', $name
        ]);

        if ($result->getStatus() != 0) {
            // Not found
            return null;
        }

        $lines = explode("\n", trim($result->getStdOut()));

        $dependsOn = [];
        $requiredBy = [];

        foreach ($lines as $line) {
            if (str_starts_with($line, 'Depends On')) {
                $parts = explode(':', $line);
                $value = trim($parts[1]);
                if ($value != 'None') {
                    $dependsOn = preg_split('/\s+/', $value, -1, PREG_SPLIT_NO_EMPTY);
                }
            } elseif (str_starts_with($line, 'Required By')) {
                $parts = explode(':', $line);
                $value = trim($parts[1]);
                if ($value != 'None') {
                    $requiredBy = preg_split('/\s+/', $value, -1, PREG_SPLIT_NO_EMPTY);
                }
            }
        }

        $info = new PkgInfo($name, $dependsOn, $requiredBy);

        if ($this->cache) {
            $this->cache->set($info);
        }

        return $info;
    }
}
