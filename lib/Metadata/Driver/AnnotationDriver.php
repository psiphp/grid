<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use Psi\Component\Grid\Metadata\ActionMetadata;
use Psi\Component\Grid\Metadata\Annotations\Grid;
use Psi\Component\Grid\Metadata\ClassMetadata;
use Psi\Component\Grid\Metadata\ColumnMetadata;
use Psi\Component\Grid\Metadata\FilterMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Metadata\SourceMetadata;
use Psi\Component\Grid\Metadata\ViewMetadata;

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
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Grid) {
                $grids[$annotation->name] = $this->getGridMetadata($annotation);
            }
        }

        if (empty($grids)) {
            return;
        }

        return new ClassMetadata($class->getName(), $grids);
    }

    private function getGridMetadata(Grid $gridAnnot)
    {
        $columns = [];
        foreach ($gridAnnot->columns as $columnAnnot) {
            $columns[$columnAnnot->name] = new ColumnMetadata(
                $columnAnnot->name,
                DriverUtil::getSourceMetadata($columnAnnot->source),
                DriverUtil::getViewMetadata($columnAnnot->view)
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
            $gridAnnot->pageSize
        );
    }
}
