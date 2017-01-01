# Psi Grid

[![experimental](http://badges.github.io/stability-badges/dist/experimental.svg)](http://github.com/badges/stability-badges)
[![Build Status](https://travis-ci.org/psiphp/grid.svg?branch=master)](https://travis-ci.org/psiphp/grid)
[![StyleCI](https://styleci.io/repos/72853910/shield)](https://styleci.io/repos/72853910)
[![Scrutinizer Code
Quality](https://scrutinizer-ci.com/g/psiphp/grid/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/psiphp/grid/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/psiphp/grid/version.png?format=plastic)](https://packagist.org/packages/psiphp/grid)
[![Total Downloads](https://poser.pugx.org/psiphp/grid/d/total.png?format=plastic)](https://packagist.org/packages/psiphp/grid)


This component is part of the Psi Content Management Framework

The grid component provides flexible data grids for objects based on class metadata.

```php
$gridFactory = GridFactoryBuilder::createWithDefaults(new OrmAgent($entityManager))

    ->addAnnotationDriver()
    ->createGridFactory();

$grid = $gridFactory->createGrid(MyEntity::class, []);

$view = $grid->createView();
```


## Documentation

See the documentation at [readthedocs](http://psiphp.readthedocs.io/projects/grid/en/latest/).

## Installation

Require in `composer.json`:

```bash
$ composer require 'psiphp/grid'
```

## Contributing

All contributions are welcome, go ahead and make a PR!
