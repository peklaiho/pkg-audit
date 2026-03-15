<?php
namespace PekLaiho\PkgAudit;

class AuditResult
{
    public function __construct(
        protected array $missingPackages,
        protected array $extraPackages
    ) {

    }

    public function getMissingPackages(): array
    {
        return $this->missingPackages;
    }

    public function getExtraPackages(): array
    {
        return $this->extraPackages;
    }
}
