<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

use Psi\Component\Grid\Metadata\SourceMetadata;
use Psi\Component\Grid\Metadata\ViewMetadata;

final class ColumnMetadata
{
    private $name;
    private $type;
    private $options = [];

    public function __construct(
        string $name,
        SourceMetadata $source,
        ViewMetadata $view
    ) {
        $this->source = $source;
        $this->view = $view;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSource(): SourceMetadata
    {
        return $this->source;
    }

    public function getView(): ViewMetadata
    {
        return $this->view;
    }
}
