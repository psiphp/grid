<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Model;

class Article
{
    private $title;
    private $number;
    private $date;

    public function __construct(string $title, int $number)
    {
        static $id = 0;

        $this->id = ++$id;
        $this->title = $title;
        $this->date = new \DateTime('2016-01-01');
    }

    public function getId() 
    {
        return $this->id;
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

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }
}
