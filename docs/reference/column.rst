Column Reference
================

This section contains a reference for all columns types availble in this
library.

Each column type will be detailed and a template example will be shown with
the Twig syntax.

The following options can be applied to ALL columns:

- ``cell_template``: Override the cell template.
- ``header_template``: Override the header template.

.. _column_property:

PropertyColumn
--------------

+--------+----------------------------+
|Parent  | *None*                     |
+--------+----------------------------+
|Alias   | ``property``               |
+--------+----------------------------+

Column used as a base for other types, uses the `Symfony property accessor`_ to
retrieve a value and set it on the cell view.

**Options**:

- *Global and inherited*.
- ``property``: Property to access, defaults to the name of the column.

.. note::

    In the simple case you will be accessing a property on an object, but if
    you are selecting from an array you will need to use the square bracket
    notation, i.e. `[email]` instead of `email`.

**Example template**:

.. code-block:: html

    <td>{{ cell.value }}</td>

.. _column_boolean:

Boolean Column
--------------

+--------+----------------------------+
|Parent  | :ref:`column_property`     |
+--------+----------------------------+
|Alias   | ``boolean``                |
+--------+----------------------------+

Represents a column of boolean values.

**Options**: *Global and inherited only*.

**Example template**:

.. code-block:: html

    <td>{{ cell.value ? 'YES' : 'NO' }}</td>

.. _column_datetime:

DateTimeColumn
--------------

+--------+----------------------------+
|Parent  | :ref:`column_property`     |
+--------+----------------------------+
|Alias   | ``datetime``               |
+--------+----------------------------+

For dates and times or both.

**Options**:

- *Global and inherited*
- ``format``: Format to use (as with the PHP date function).

.. code-block:: html

    <td>{{ cell.value.format(cell.parameters['format'] }}</td>

.. _column_money:

MoneyColumn
-----------

+--------+----------------------------+
|Parent  | :ref:`column_property`     |
+--------+----------------------------+
|Alias   | ``money``                  |
+--------+----------------------------+

Column which can be used to represent money.

**Options**:

- *Global and inherited*
- ``curreny``: Currency code. **Default**: ``EUR``.
- ``divisor``: Divide the money by this amount when displaying (i.e. you
  store your money in its lowest possible denominator, e.g.
  cents, so it should be divided by 100 when being displayed (euros and
  cents). **Default** ``1``.
- ``scale``: Number of decimal places.

**Example template**:

.. code-block:: html

    <td>&euro;{{ cell.value }}</td>

.. _column_select:

Select Column
-------------

+--------+----------------------------+
|Parent  | :ref:`column_property`     |
+--------+----------------------------+
|Alias   | ``select`                  |
+--------+----------------------------+

The select column is a checkbox column which is used in conjunction with bulk
actions.

The value of the checkbox should be the ID of the class you are affecting, and
it defaults to ``id`` (override with the inherited ``property`` options).

.. note::

    Your grid must be wrapped in a ``<form>`` if you wish to use bulk actions.

**Options**: *Global and inherited only*.

**Example template**:

.. code-block:: html

    <td>
        <input type="checkbox" name="{{ cell.parameters.input_name }}[{{ cell.value }}]" value="{{ cell.value }}"/>
    </td>

.. _column_text:

Text Column
--------------

+--------+----------------------------+
|Parent  | :ref:`column_property`     |
+--------+----------------------------+
|Alias   | ``text``                   |
+--------+----------------------------+

Represents a text column and allows a truncate length to be specified.

.. note::

    The cell will not truncate the value for you, it is left as a task for the
    templating layer. If using Twig install the `Twig Extensions`_ package and
    enable the ``TextExtension``.

**Options**: 

- *Global and inherited*.
- ``truncate``: Truncate the text to this length.

**Example template**:

.. code-block:: html

    <td>
        {{ cell.value|trunacate(cell.parameters['truncate']) }}
    </td>
