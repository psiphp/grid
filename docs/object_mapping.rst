Object Mapping
==============

Grids are always associated with a single model (although by way of a query they
may join other models and select virtual columns).

Currently there exists an annotation and array (hence YAML) driver for
defining grids.

Using the grid factory builder you can easily add metadata drivers:

.. note::

    In this section, as in the reset of the documentation, will will use
    annotation mapping in examples. Annotations are good for rapid development
    and keeping information about objects close to the objects.

Instantiation
-------------

Array driver:

.. code-block:: php

    <?php
    $gridFactory = GridFactoryBuilder::createWithDefaults($agentFinder)
        ->addArrayDriver([
            // mapping
        ])
        ->createGridFactory();

Annotation driver:

.. code-block:: php

    <?php
    $gridFactory = GridFactoryBuilder::createWithDefaults($agentFinder)
        ->addAnnotationDriver()
        ->createGridFactory();

Annotation Example
------------------

The following is a simple grid:

.. code-block:: php

    <?php

    use Psi\Component\Grid\Metadata\Annotations as Grid;

    /**
     * @Grid\Grid(
     *     name="default",
     *     query="details",
     *     columns={
     *         @Grid\Column(name="active", type="boolean"),
     *         @Grid\Column(name="name", type="text"),
     *         @Grid\Column(name="email", type="text"),
     *     },
     *     filters={
     *         @Grid\Filter(name="active", type="boolean"),
     *         @Grid\Filter(name="name", type="string"),
     *         @Grid\Filter(name="email", type="string"),
     *     },
     * )
     */
    class User
    {
        private $active;
        private $name;
        private $email;

        // ...
    }

and a more complicated one with a query:

.. code-block:: php

    <?php

    use Psi\Component\Grid\Metadata\Annotations as Grid;

    /**
     * @Grid\Query(
     *     name="details",
     *     selects={ 
     *         "a.id": "id", 
     *         "a.active": "active", 
     *         "a.name": "name", 
     *         "a.email": "email", 
     *         "p.title": "productTitle"
     *     },
     *     joins={ 
     *         { "join": "a.product", "alias": "p" }
     *     },
     * )
     * @Grid\Grid(
     *     query="details",
     *     columns={
     *         @Grid\Column(name="active", type="boolean"),
     *         @Grid\Column(name="email", type="text", options={"sort_field": "a.email"}),
     *         @Grid\Column(name="name", type="text", options={"sort_field": "a.name"}),
     *     },
     *     filters={
     *         @Grid\Filter(name="email", type="string", options={"comparators": {"contains"}}),
     *         @Grid\Filter(name="active", type="boolean", options={"label": "Membre active"}),
     *         @Grid\Filter(name="name", type="string", options={"comparators": {"contains"}}),
     *     },
     * )
     */
    class User
    {
        private $active;
        private $name;
        private $email;

        // ...
    }

Array Example
-------------

The following is a simple grid:

.. code-block:: php

    <?php

    use Psi\Component\Grid\GridFactoryBuilder;

    $gridFactory = GridFactoryBuilder::createWithDefaults($agentFinder)
        ->addArrayDriver([
            'grids' => [
                'main' => [
                    'columns' => [
                        'active' => [
                            'type' => 'boolean',
                        ],
                        'email' => [
                            'type' => 'text',
                        ],
                        'name' => [
                            'type' => 'text',
                        ],
                    ],
                    'filters' => [
                        'active' => [
                            'type' => 'boolean',
                        ],
                        'email' => [
                            'type' => 'string',
                        ],
                        'name' => [
                            'type' => 'string',
                        ],
                    ],
                ]
            ]
        ])->createGridFactory();

and a more complicated one with a query:

.. code-block:: php

    <?php

    $gridFactory = GridFactoryBuilder::createWithDefaults($agentFinder)
        ->addArrayDriver([
            'queries' => [
                'details' => [
                    'selects' => [
                        'a.id' => 'id',
                        'a.active' => 'active',
                        'a.name' => 'name',
                        'a.email' => 'email',
                        'p.title' => 'productTitle',
                    ],
                    'joins' => [
                        [ 'join' => 'a.product', 'alias' => 'p' ],
                    ],
                ],
            ],
            'grids' => [
                'main' => [
                    'query' => 'details',
                    'columns' => [
                        'active' => [
                            'type' => 'boolean',
                        ],
                        'email' => [
                            'type' => 'text',
                            'options' => [
                                'sort_field' => 'a.email',
                            ],
                        ],
                        'name' => [
                            'type' => 'text',
                            'options' => [
                                'sort_field' => 'a.name',
                            ],
                        ],
                    ],
                    'filters' => [
                        'active' => [
                            'type' => 'boolean',
                            'options' => [
                                'comparators' => [ 'contains' ],
                            ],
                        ],
                        'email' => [
                            'type' => 'string',
                            'options' => [
                                'label' => 'Member active',
                            ],
                        ],
                        'name' => [
                            'type' => 'string',
                            'options' => [
                                'comparators' => [ 'contains' ],
                            ],
                        ],
                    ],
                ]
            ]
        ])->createGridFactory();
