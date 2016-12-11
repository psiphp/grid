<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

interface ActionResponseInterface
{
    public function hasRedirect(): bool;

    public function getRedirect(): string;

    public function getRedirectParams(): array;

    public function getErrors(): array;

    public function hasErrors(): bool;

    public function getAffectedRecordCount(): int;

    public function hasAffectedRecords(): bool;
}
