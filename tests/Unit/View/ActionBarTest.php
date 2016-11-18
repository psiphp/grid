<?php

namespace Psi\Component\Grid\Tests\Unit\View;

use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\Grid\View\ActionBar;

class ActionBarTest extends \PHPUnit_Framework_TestCase
{
    private $bar;

    public function setUp()
    {
        $gridMetadata = MetadataUtil::createGrid('hello', [
            'actions' => [
                'foobar' => [
                    'type' => 'barfoo',
                ],
            ],
        ]);

        $this->bar = new ActionBar($gridMetadata);
    }

    public function testActionBar()
    {
        $actions = $this->bar->getAvailableActionNames();
        $this->assertEquals(['foobar' => 'foobar'], $actions);
        $this->assertEquals('action_name', $this->bar->getInputName());
    }
}
