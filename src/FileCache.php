<?php
namespace PekLaiho\PkgAudit;

class FileCache implements IPackageCache
{
    public function __construct(
        protected string $dir
    ) {
        if (!str_ends_with($this->dir, DIRECTORY_SEPARATOR)) {
            $this->dir = $this->dir . DIRECTORY_SEPARATOR;
        }

        // Try to create directory if it does not exist
        if (!is_dir($this->dir)) {
            $result = (new ShellRunner())->run([
                'mkdir', '-p', $this->dir
            ]);

            if ($result->getStatus() !== 0) {
                Utils::error('Unable to create directory: ' . $this->dir);
            }
        }
    }

    public function get(string $name): ?PkgInfo
    {
        $file = $this->dir . $name;

        if (is_readable($file)) {
            return unserialize(file_get_contents($file));
        }

        return null;
    }

    public function set(PkgInfo $info): void
    {
        $file = $this->dir . $info->getName();

        file_put_contents($file, serialize($info));
    }
}
