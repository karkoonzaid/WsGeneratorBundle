Generating a New Controller
===========================

Usage
-----

The ``ws:generate:controller`` command generates a new Controller including
actions, tests, templates and routing.

By default the command is run in the interactive mode and asks questions to
determine the bundle name, location, configuration format and default
structure:

.. code-block:: bash

    $ php app/console ws:generate:controller

The command can be run in a non interactive mode by using the
``--no-interaction`` option without forgetting all needed options:

.. code-block:: bash

    $ php app/console ws:generate:controller --no-interaction --controller=WsBlogBundle:Post

Available Options
-----------------

* ``--controller``: The controller name given as a shortcut notation containing 
  the bundle name in which the controller is located and the name of the 
  bundle. For instance: ``WsBlogBundle:Post`` (creates a ``PostController``
  inside the ``WsBlogBundle`` bundle):

    .. code-block:: bash

        $ php app/console ws:generate:controller --controller=WsBlogBundle:Post

* ``--actions``: The list of actions to generate in the controller class. This
  has a format like ``%actionname%:%route%:%template`` (where ``:%template%``
  is optional:

    .. code-block:: bash

        $ php app/console ws:generate:controller --actions="showPostAction:/article/{token} getListAction:/_list-posts/{max}:WsBlogBundle:Post:list_posts.html.twig"
        
        # or
        $ php app/console ws:generate:controller --actions=showPostAction:/article/{token} --actions=getListAction:/_list-posts/{max}:WsBlogBundle:Post:list_posts.html.twig

* ``--route-format``: (**annotation**) [values: yml, xml, php or annotation] 
  This option determines the format to use for routing. By default, the 
  command uses the ``annotation`` format:

    .. code-block:: bash

        $ php app/console ws:generate:controller --route-format=annotation

* ``--template-format``: (**twig**) [values: twig or php] This option determines
  the format to use for the templates. By default, the command uses the ``twig``
  format:

    .. code-block:: bash

        $ php app/console ws:generate:controller --template-format=twig
