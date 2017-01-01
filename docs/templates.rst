Templates
=========

The Psi Grid component does not include any templates by default. This is
because templating is highly specific - it is not possible to support all
the different CSS frameworks which are in the wild.

Often in a project you will discover that you will override templates anyway,
which leads to templates being distributed between your project and vendor
libraries, increasing complexity.

We recommend that you code your own templates. This is made easier by the fact
that the grid system will generate a **view** which can be traversed and
contains most of the logic that you will require.

This chapter will expose you to the Grid View API and show some example
templates.

Grid View API
-------------

You create the grid view from the `Grid` object:

.. code-block:: php

    <?php

    /**
     * @var Psi\Component\Grid\GridFactory
     */
    $gridFactory = // get grid factory
    $grid = $gridFactory->createGrid(MyObject::class, [ /** grid config */]);

    // create the grid view
    $view = $grid->createView();

    // paginator
    $paginator = $view->getPaginator(); // Psi\Component\Grid\View\Paginator
    $paginator->getPageSize();
    $paginator->getCurrentPage();
    $paginator->getNumberOfRecords();
    $paginator->isLastPage;
    $paginator->getUrlParametersForPage(2);

    // filter bar
    $filterBar = $view->getFilter(); // Psi\Component\Grid\View\FilterBar
    $filterBar->getForm(); // return the Symfony form view instance
    $filterBar->getUrlParametersForReset();
    $filterBar->getUrlParametersForFilter();

    // table
    $table = $view->getTable();
    $table->getHeaders(); // Psi\Component\Grid\View\Header[]

    // table body
    $body = $table->getBody(); // iterable Psi\Component\Grid\View\Body

    /** @var Psi\Component\Grid\View\Row */
    foreach ($body as $row) {

        /** @var Psi\Component\Grid\View\Cell */
        foreach ($row as $cell) {
            $cell->getContext(); // return the context (e.g. the object / data set of the row)
            $cell->getTemplate(); // return template reference to render the cell
            $cell->value; // property containing value of cell
            $cell->parameters; // array containing parameters of the cell
        }
    }

Example Twig Template
---------------------

The following Twig template is intended to help you get started, it uses Bootstrap
3 and Symfony routing functions:

.. code-block:: html+twig

    <div class="row">
        <div class="col-md-8">
            {# create a form here, as actions will be performed based on selected cells #}
            <form class="form-inline" action="#" method="POST">

                {# Display the grid #}
                <table class="dashboardlisting listing">
                    <thead>
                        <tr>
                            {% for header in grid.table.headers %}
                                <th>
                                {{ header.label|trans()|ucfirst }}
                                {% if header.canBeSorted %}
                                    {% if false == header.sorted %}
                                        <a href="{{ path(currentRouteName, header.getUrlParametersForSort('asc')) }}"><i class="fa fa-sort"></i></a>

                                    {% else %}
                                        <a href="{{ path(currentRouteName, header.getUrlParametersForSort(header.isSortAscending ? 'desc' : 'asc')) }}">
                                            <i class="fa fa-sort-{{ header.isSortAscending ? 'down' : 'up' }}"></i>
                                        </a>
                                    {% endif %}
                                {% endif %}
                                </th>
                            {% endfor %}
                        </tr>
                    </thead>

                    {% for row in grid.table.body %}
                        <tr>
                            {% for cell in row %}
                                {# You will need to create each cell template as required #}
                                {% include "grid/" ~ cell.template ~ "Cell.twig" %}
                            {% endfor %}
                            {# Maybe you want to add more cells here, e.g. for actions #}
                        </tr>
                    {% endfor %}
                </table>

                {# show the bulk actions #}
                <div>
                    <div class="row">
                        <div class="col-md-6">
                            {% if grid.actionBar.availableActionNames %}
                                <label for="{{ grid.actionBar.inputName }}">Avec les fiches selectioné: </label>
                                <select name="{{ grid.actionBar.inputName }}" class="form-control">
                                    {% for actionId, action in grid.actionBar.availableActionNames %}
                                        <option value="{{ actionId }}">{{ action }}</option>
                                    {% endfor %}
                                </select>
                                <input type="submit" class="btn btn-primary" value="Executer" />
                            {% endif %}
                        </div>
                        <div class="col-md-6">
                            <div class="pull-right">
                                {# see example pager below #}
                                {% include "pager.twig" %}
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>

        {# show the filter bar #}
        <aside class="col-md-4">
            {% if grid.filter.form.children|length > 0 %}
                <div class="panel panel-default">
                    <div class="panel-heading">Filter</div>
                    <div class="panel-body">
                        <form action="" method="GET">
                            {% for child in grid.filter.form %}
                            <div class="form-group">
                                {{ form_label(child) }}
                                {% for childChild in child %}
                                    {{ form_widget(childChild) }}
                                {% endfor %}
                            </div>
                            {% endfor %}
                            <input class="btn btn-primary" type="submit" value="Filter" />
                            <a class="btn btn-secondary" href="{{ path(currentRouteName) }}">Reset</a>
                        </form>
                    </div>
                </div>
            {% endif %}
        </aside>
    </div>

The following a simple pager template:

.. code-block:: html+twig

    {% set pager = grid.paginator %}
    <ul class="pagination pagination-centered">
        {% if pager.currentPage > 1 %}
            <li>
                <a class="active" href="{{ path(routeName, pager.urlParametersForPage(pager.currentPage - 1)) }}" >
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>
        {% else %}
            <li>
                <a class="icon item">
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>
        {% endif %}
        <li>
            <A class="icon item">{{ pager.currentPage }} / {{ pager.numberOfPages }}</a>
        </li>
        <li>
        {% if pager.isLastPage %}
            <a class="icon item">
                <i class="fa fa-angle-right"></i>
            </a>
        {% else %}
            <a class="icon item" href="{{ path(routeName, pager.urlParametersForPage(pager.currentPage + 1)|raw) }}" >
                <i class="fa fa-angle-right"></i>
            </a>
        {% endif %}
        </li>
        <li>
            <a class="icon item">
                <b>{{ pager.numberOfRecords }} fiches</b>
            </a>
        </li>
    </ul>
