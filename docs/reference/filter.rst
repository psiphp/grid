Filter Reference
================

The filter system generates a Symfony form, and with submitted data it creates
a query which filters the results.

.. code-block:: php

    <?php

    use Psi\Component\Grid\Metadata\Annotations as Grid;

    /**
     * @Grid\Grid(
     *     // ...
     *     filters={
     *         @Grid\Filter(name="active", type="boolean"),
     *         @Grid\Filter(name="foobar", field="a.name", type="string", options={"comparators": { "contains" }),
     *         @Grid\Filter(name="email", type="string"),
     *     },
           // ...
     * )
     */
    class User
    {
        // ...
    }

In addition to options, each filter can specify which *database* field should
act upon, in the above example we instruct the ``foobar`` column to use the
field ``a.name`` (``a`` is the default alias of the class, in this case
``User::class``).

BooleanFilter
-------------

+--------+----------------------------+
|Alias   | ``boolean``                |
+--------+----------------------------+
|Applies | When value is submitted.   |
+--------+----------------------------+

Filter grid based on boolean value, provides a on / off choice.

**Options**:

- **None**

ChoiceFilter
------------

+--------+------------------------------------------+
|Alias   | ``choice``                               |
+--------+------------------------------------------+
|Applies | When submitted and choice is not empty.  |
+--------+------------------------------------------+

Filter grid based on the field matching the value of the choice.

**Options**:

- ``choices``: Choices to use.
- ``placeholder``: Text to use for empty value.
- ``expanded``: If the form field should be expanded.
- ``multiple``: If multiple values should be allowed.

DateFilter
----------

+--------+------------------------------------------+
|Alias   | ``date``                                 |
+--------+------------------------------------------+
|Applies | When the "apply" checkbox is checked.    |
+--------+------------------------------------------+

Filter based on a date using a given comparator.

**Options**:

- ``comparators``: Array of comparators to expose (if only one is given, the
  choice will be implicit). Comparators should be one of the ``Comparison::*``
  constants.
  **Default**: ``Comparison::EQUALS``.

NumberFilter
------------

+--------+------------------------------------------+
|Alias   | ``number``                               |
+--------+------------------------------------------+
|Applies | When submitted and non-empty.            |
+--------+------------------------------------------+

Filter based on a date using a given comparator.

**Options**:

- ``comparators``: Array of comparators to expose (if only one is given, the
  choice will be implicit). Comparators should be one of the ``Comparison::*``
  constants.
  **Default**: ``Comparison::EQUALS``.

StringFilter
------------

+--------+------------------------------------------+
|Alias   | ``string``                               |
+--------+------------------------------------------+
|Applies | When submitted and non-empty.            |
+--------+------------------------------------------+

Filter based on a date using a given comparator.

**Options**:

- ``comparators``: Array of comparators to expose (if only one is given, the
  choice will be implicit). Comparators should be one of the
  ``StringFilter::TYPE_*`` constants.
  **Default**: ``StringFilter::TYPE_EQUALS``.
