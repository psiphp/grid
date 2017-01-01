Action Reference
================

Actions are performed on selected records from the current page.

In order to perform actions you need to call the ``performActionFromPostData``
method on the grid class. This method will return a response, with the number
of records affected, any errors encountered and potentially a redirect:

.. code-block:: php

    <?php

    // in your controller
    $grid = // create the grid from the GridFactory

    if ($request->getMethod() === 'POST') {
        $actionResponse = $grid->performActionFromPostData($request->request->all());

        if ($actionResponse->hasRedirect()) {
            return new RedirectResponse($this->urlGenerator->generate(
                $actionResponse->getRedirect(), $actionResponse->getRedirectParams()
            ));
        }

        if ($errors = $actionResponse->getErrors()) {
            $this->flashLogger->error('- ' . implode('- ' . PHP_EOL, $actionResponse->getErrors()));
        }

        $this->flashLogger->info(sprintf('%s fiche(s) affecté', $actionResponse->getAffectedRecordCount()));

        return new RedirectResponse($this->urlGenerator->generate(
            $request->attributes->get('_route'),
            $request->query->all()
        ));
    }

DeleteAction
------------

+--------+----------------------------+
|Alias   | ``delete``                 |
+--------+----------------------------+

Delete selected records.

**Options**: *None*
