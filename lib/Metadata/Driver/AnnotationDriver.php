<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use Psi\Component\Grid\Metadata\ActionMetadata;
use Psi\Component\Grid\Metadata\Annotations;
use Psi\Component\Grid\Metadata\ClassMetadata;
use Psi\Component\Grid\Metadata\ColumnMetadata;
use Psi\Component\Grid\Metadata\FilterMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Metadata\QueryMetadata;

class AnnotationDriver implements DriverInterface
{
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Reader $reader = null)
    {
        $this->reader = $reader ?: new AnnotationReader();
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $annotations = $this->reader->getClassAnnotations($class);

        $grids = [];
        $queries = [];

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Annotations\Grid) {
                $grids[$annotation->name] = $this->getGridMetadata($annotation);
            }

            if ($annotation instanceof Annotations\Query) {
                $queries[$annotation->name] = $this->getQueryMetadata($annotation);
            }
        }

        if (empty($grids)) {
            return;
        }

        return new ClassMetadata($class->getName(), $grids, $queries);
    }

    private function getGridMetadata(Annotations\Grid $gridAnnot)
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

        $actions = [];
        foreach ($gridAnnot->actions as $actionAnnot) {
            $actions[$actionAnnot->name] = new ActionMetadata(
                $actionAnnot->name,
                $actionAnnot->type,
                $actionAnnot->options
            );
        }

        return new GridMetadata(
            $gridAnnot->name,
            $columns,
            $filters,
            $actions,
            $gridAnnot->pageSize,
            $gridAnnot->query
        );
    }

    private function getQueryMetadata(Annotations\Query $query)
    {
        return new QueryMetadata(
            $query->name,
            $query->selects,
            $query->joins,
            $query->criteria,
            $query->having,
            $query->groupBys
        );
    }
}
