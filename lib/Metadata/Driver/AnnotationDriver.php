<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use Psi\Component\Grid\Metadata\Annotations\Grid;
use Psi\Component\Grid\Metadata\ClassMetadata;
use Psi\Component\Grid\Metadata\ColumnMetadata;
use Psi\Component\Grid\Metadata\FilterMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;

class AnnotationDriver implements DriverInterface
{
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $annotations = $this->reader->getClassAnnotations($class);

        $grids = [];
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Grid) {
                $grids[$annotation->name] = $this->getGridMetadata($annotation);
            }
        }

        return new ClassMetadata($class->getName(), $grids);
    }

    private function getGridMetadata(Grid $gridAnnot)
    {
        $columns = [];
        foreach ($gridAnnot->columns as $columnAnnot) {
            $columns[$columnAnnot->name] = new ColumnMetadata(
                $columnAnnot->name,
                $columnAnnot->type,
                $columnAnnot->options
            );
        }

        $filters = [];
        foreach ($gridAnnot->filters as $filterAnnot) {
            $filters[$filterAnnot->name] = new FilterMetadata(
                $filterAnnot->name,
                $filterAnnot->type,
                $filterAnnot->field,
                $filterAnnot->options
            );
        }

        return new GridMetadata(
            $gridAnnot->name,
            $columns,
            $filters,
            $gridAnnot->pageSize
        );
    }
}
