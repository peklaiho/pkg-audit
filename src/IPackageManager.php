<?php
namespace PekLaiho\PkgAudit;

interface IPackageManager
{
    public function getFullPackageInfo(): array;
    public function getInstalledPackages(): array;
    public function getPackageInfo(string $name): ?PkgInfo;
}
