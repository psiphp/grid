<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Functional\Metadata\Driver\Model;

use Psi\Component\Grid\Metadata\Annotations as Grid;

/**
 * @Grid\Query(
 *     name="details",
 *     selects={ "a.title": "title" },
 *     joins={ { "join": "a.address", "address" } },
 *     criteria={ "eq": { "a.active": true } }
 * )
 *
 * @Grid\Grid(
 *     name="main",
 *     columns={
 *         @Grid\Column(
 *             name="title",
 *             type="property",
 *             options={
 *                 "property": "name"
 *             },
 *             tags="foo"
 *         ),
 *         @Grid\Column(
 *             name="price",
 *             type="property",
 *             groups={"main", "foobar"},
 *             tags={"foo"}
 *         ),
 *     },
 *     filters={
 *         @Grid\Filter(
 *             name="title",
 *             type="string",
 *             options={
 *                 "foo": "bar"
 *             },
 *             tags={"foo"}
 *         ),
 *         @Grid\Filter(
 *             name="price",
 *             field="cost",
 *             type="number",
 *             tags={"foo"}
 *         ),
 *     },
 *     actions={
 *         @Grid\Action(
 *             name="delete_selected",
 *             type="delete",
 *             tags={"foo"}
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
