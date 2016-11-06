<?php

namespace Psi\Component\Grid\Tests\Model;

class Article
{
    private $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function getTitle() 
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
