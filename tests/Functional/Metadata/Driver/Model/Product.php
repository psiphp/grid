<?php

namespace Psi\Component\Grid\Tests\Functional\Metadata\Driver\Model;

use Psi\Component\Grid\Metadata\Annotations as Grid;

/**
 * @Grid\Grid(
 *     name="main",
 *     columns={
 *         @Grid\Column(
 *             name="title",
 *             source={"type": "property"},
 *             view="scalar",
 *             options={
 *                 "property": "name",
 *                 "long_format": "YYYY"
*                 }
 *         ),
 *         @Grid\Column(
 *             name="price"
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
 *     actions={
 *         @Grid\Action(
 *             name="delete_selected",
 *             type="delete"
 *         )
 *     },
 *
 *     pageSize=10
 * )
 * @Grid\Grid(name="second");
 */
class Product
{
    public $name;
    public $price;
}
