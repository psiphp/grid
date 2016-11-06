<?php

namespace Psi\Component\Grid\Tests\Model;

class Article
{
    private $title;
    private $number;

    public function __construct(string $title, int $number)
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

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }
}
