<?php
namespace PekLaiho\PkgAudit;

interface IPackageCache
{
    public function get(string $name): ?PkgInfo;
    public function set(PkgInfo $info): void;
}
