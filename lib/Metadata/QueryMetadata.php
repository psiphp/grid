<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

final class QueryMetadata
{
    private $name;
    private $selects;
    private $joins;
    private $criteria;
    private $having;
    private $groupBys;

    public function __construct(
        string $name,
        array $selects = [],
        array $joins = [],
        array $criteria = [],
        array $having = null,
        array $groupBys = []
    ) {
        $this->name = $name;
        $this->selects = $selects;
        $this->joins = $joins;
        $this->criteria = $criteria;
        $this->having = $having;
        $this->groupBys = $groupBys;
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

    public function getHaving()
    {
        return $this->having;
    }

    public function hasHaving()
    {
        return null !== $this->having;
    }

    public function getGroupBys(): array
    {
        return $this->groupBys;
    }
}
