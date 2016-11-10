<?php

namespace Psi\Component\Grid\Tests\Functional\Metadata\Driver\Model;

use Psi\Component\Grid\Metadata\Annotations as Grid;

/**
 * @Grid\Grid(
 *     name="main",
 *     columns={
 *         @Grid\Column(
 *             name="title",
 *             type="property",
 *             options={
 *                 "property": "name"
 *             }
 *         ),
 *         @Grid\Column(
 *             name="price",
 *             type="property"
 *         ),
 *     },
 *     filters={
 *         @Grid\Filter(
 *             name="title",
 *             type="string",
 *             options={
 *                 "foo": "bar"
 *             }
 *         ),
 *         @Grid\Filter(
 *             name="price",
 *             field="cost",
 *             type="number"
 *         ),
 *     },
 *     pageSize=10
 * )
 * @Grid\Grid(name="second");
 */
class Product
{
    public $name;
    public $price;
}
