About
=====

The grid component provides a framework-independent solution for rendering
sorted, paginated and filtered grids of data from any data source supported by
the `Psi Object Agent component`_.

Grids can currently be defined using **Annotations** and arrays (and so YAML
files). XML support will be supported in the future.

Features
--------

- **Templateless**: Write your own templates, you will anyway.
- **Solid object structure**: Much less logic to write in templates.
- **Annotation support**: Keep information about your objects with your objects.
- **Query select support**: Select values from your data source, or just use
  the values the object provides.
- **Column types**: Specify column types (e.g. boolean ,datetime, text).
- **Filters**: Embraces the Symfony form framework to provide a powerful filter
  system.
- **Actions**: Apply bulk actions to selected records.
