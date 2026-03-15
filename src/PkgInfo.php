<?php
namespace PekLaiho\PkgAudit;

class PkgInfo
{
    public function __construct(
        protected string $name,
        protected array $dependsOn,
        protected array $requiredBy
    ) {

    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDependsOn(): array
    {
        return $this->dependsOn;
    }

    public function getRequiredBy(): array
    {
        return $this->requiredBy;
    }

    public function requires(string|PkgInfo $other): bool
    {
        if (!is_string($other)) {
            $other = $other->getName();
        }

        return in_array($other, $this->dependsOn);
    }

    public function requiredBy(string|PkgInfo $other): bool
    {
        if (!is_string($other)) {
            $other = $other->getName();
        }

        return in_array($other, $this->requiredBy);
    }
}
