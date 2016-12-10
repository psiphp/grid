<?php

namespace Psi\Component\Grid;

class QueryFactory
{
    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    public function __construct(
        MetadataFactory $metadataFactory
    )
    {
        $this->metadataFactory = $metadataFactory;
    }

    public function createQuery(string $classFqn, string $name)
    {
        if (null === $metadata = $this->metadataFactory->getMetadataForClass($context->getClassFqn())) {
            throw new \InvalidArgumentException('Could not locate grid metadata');
        }


    }
}
