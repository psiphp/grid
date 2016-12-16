<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Metadata\MetadataFactory;
use Psi\Component\ObjectAgent\Query\Converter\ArrayConverter;

class QueryFactory
{
    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @var ArrayConverter
     */
    private $converter;

    public function __construct(
        MetadataFactory $metadataFactory,
        ArrayConverter $converter = null
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->converter = $converter ?: new ArrayConverter();
    }

    public function createQuery(string $classFqn, string $name)
    {
        if (null === $metadata = $this->metadataFactory->getMetadataForClass($classFqn)) {
            throw new \InvalidArgumentException('Could not locate grid metadata');
        }

        $queries = $metadata->getQueries();

        if (!isset($queries[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'Query "%s" for "%s" is not known. Known queries: "%s"',
                $name, $classFqn, implode('", "', array_keys($queries))
            ));
        }

        $query = $queries[$name];

        return $this->converter->__invoke([
            'from' => $classFqn,
            'selects' => $query->getSelects(),
            'joins' => $query->getJoins(),
            'criteria' => $query->getCriteria() ?: null,
            'having' => $query->hasHaving() ? $query->getHaving() : null,
            'groupBys' => $query->getGroupBys()
        ]);
    }
}
