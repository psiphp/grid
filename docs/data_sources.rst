Data Sources
============

The Psi grid depends on the `Psi Object Agent`_ component to provide data. The
Object Agent component provides backends for Doctrine ORM, Doctrine PHPCR ODM,
and the Doctrine Collections library (which is primarily useful for testing
purposes).

What is more, through the use of the ``AgentFinder`` class, the grid system
can support many data sources in a single factory instance.

The following sections demonstrate how to instantiate the grid using each of
these systems.

In this section we will assume that you are using the :ref:`annotations
reference`.

Doctrine ORM
------------

.. code-block:: php

    <?php

    use Psi\Bridge\ObjectAgent\Doctrine;
    use Psi\Component\Grid\GridFactoryBuilder;
    use Psi\Component\ObjectAgent\AgentFinder;

    $entityManger = // get your entity manager here ...
    $agentFinder = new AgentFinder([
        new Doctrine\Orm\OrmAgent($entityManager)
    ]);

    $gridFactory = GridFactoryBuilder::createWithDefaults($agentFinder)
        ->addAnnotationDriver()
        ->createGridFactory();

Doctrine PHPCR ODM
------------------

.. code-block:: php

    <?php

    use Psi\Bridge\ObjectAgent\Doctrine;
    use Psi\Component\Grid\GridFactoryBuilder;
    use Psi\Component\ObjectAgent\AgentFinder;

    $entityManger = // get your document manager here ...
    $agentFinder = new AgentFinder([
        new Doctrine\PhpcrOdm\PhpcrOdmAgent($documentManager)
    ]);

    $gridFactory = GridFactoryBuilder::createWithDefaults($agentFinder)
        ->addAnnotationDriver()
        ->createGridFactory();

Doctrine Collections
--------------------

.. code-block:: php

    <?php

    use Acme\Model\Article;
    use Psi\Bridge\ObjectAgent\Doctrine;
    use Psi\Component\Grid\GridFactoryBuilder;
    use Psi\Component\ObjectAgent\AgentFinder;

    $entityManger = // get your document manager here ...
    $agentFinder = new AgentFinder([
        new Doctrine\Collections\CollectionsAgent([
            // manually create class instances
            Article::class => [
                new Article('foobar'),
                new Article('barfoo'),
                // ... 
            ]
        ])
    ]);

    $gridFactory = GridFactoryBuilder::createWithDefaults($agentFinder)
        ->addAnnotationDriver()
        ->createGridFactory();
