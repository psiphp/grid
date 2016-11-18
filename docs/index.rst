Grid
====

The grid component provides a framework-independent solution for rendering
paginated grids of data from any data source supported by the `obejct agent
component`_.

Grids are defined with metadata (either annotations or XML) and provide a
framework for defining filters, bulk actions. Grids can be sorted.

Usage
-----

The easiest way to instantiate the grid is with the ``GridBuilder`` class:

First of all you will need to instantiate an ``AgentFinder`` and at least one
``AgentInterface`` instance. The agent is responsible for fetching data from
storage. The ``AgentFinder`` is a class which will automatically select the
correct backend for any given class (providing that the given class is
supported by one of the backends).

For the benefit of this example we will use an in-memory provider:

.. code-block:: php

    <?php

    use Psi\Component\ObjectAgent\AgentFinder;
    use Psi\Bridge\ObjectAgent\Doctrine\Collections\CollectionsAgent;
    use Psi\Bridge\ObjectAgent\Doctrine\Collections\Store;

    $agnetFinder = new AgentFinder([
        new CollectionsAgent(new Store([
            Article::class => [
                new Article('foobar'),
                new Article('barfoo'),
                // ... 
            ]
        ]))
    ]);

We can now create the grid factory, in this example we will add an array-based
metadata driver:

.. code-block:: php

    use Psi\Component\Grid\GridFactoryBuilder;

    $gridFactory = GridFactoryBuilder::createWithDefaults($agentFinder)
        ->addArrayDriver([
            Article::class => [
                'grids' => [
                    'main' => [
                        'columns' => [
                             'title' => [
                                 'type' => 'property',
                             ],
                             'description' => [
                                 'type' => 'property',
                             ],
                        ],
                    ],
                ],
            ],
        ])
    ->createGridFactory();

.. note::

    You can also use annotation or XML metadata drivers.

You can now create the grid instance for any given class name. The grid
instance permits you to create the *grid view* and to handle bulk actions.

.. code-block:: php

    $grid = $gridFactory->createGrid(Article::class, [ /* options */ ]]);
    $gridView = $grid->createView();

The grid view should be passed to your templating system. It contains
everything you need to render your grid including the filters, pagination,
headers and the table itself:

.. code-block:: php

    // do some baseic rendering.
    echo '<table>';
    foreach ($gridView->getTable()->getBody() as $row) {
        echo '<tr>';
        foreach ($row as $cell) {
             echo '<td>' . $cell->getValue() . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';

.. note::

    The grid view has access to the pagination objects, as well as the filter and
    bulk-action bars. These will be explained later.

.. note::

    You may want to consider using the ``object_render`` component to render
    your grid effectively.
