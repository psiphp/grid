<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

final class QueryMetadata
{
    private $name;
    private $selects = [];
    private $joins = [];
    private $criteria = [];

    public function __construct(
        string $name,
        array $selects,
        array $joins,
        array $criteria
    )
    {
        $this->name = $name;
        $this->selects = $selects;
        $this->joins = $joins;
        $this->criteria = $criteria;
    }

    public function getName() 
    {
        return $this->name;
    }

    public function getSelects(): array
    {
        return $this->selects;
    }

    public function getJoins(): array
    {
        return $this->joins;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }
}
