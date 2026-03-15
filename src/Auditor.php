<?php
namespace PekLaiho\PkgAudit;

class Auditor
{
    public function audit(array $requested, array $installed): AuditResult
    {
        $missingPackages = [];
        $extraPackages = [];

        foreach ($requested as $name) {
            if (!array_key_exists($name, $installed)) {
                $missingPackages[] = $name;
            }
        }

        foreach ($installed as $pkg) {
            if (!$this->isRequested($pkg, $requested, $installed)) {
                $extraPackages[] = $pkg->getName();
            }
        }

        return new AuditResult($missingPackages, $extraPackages);
    }

    protected function isRequested(PkgInfo $pkg, array $requested, array $installed): bool
    {
        if (in_array($pkg->getName(), $requested)) {
            return true;
        }

        foreach ($pkg->getRequiredBy() as $otherName) {
            if (array_key_exists($otherName, $installed) &&
                $this->isRequested($installed[$otherName], $requested, $installed)) {
                return true;
            }
        }

        return false;
    }
}
