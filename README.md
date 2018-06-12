# MenuServiceBundle

This bundle provide a menu service for knp/menu-bundle that is configurable by config file.

## Installation

``` sh
composer require kalamu/menu-service-bundle:^2.0
```

Puis ajouter dans `AppKernel.php`

``` php
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Kalamu\MenuServiceBundle\KalamuMenuServiceBundle(),
        );
    }
```

## How to build a menu

To build a menu, you just have to add the items in your `app/config/config.yml` file :

``` yaml
kalamu_menu_service:
    menu_name:
        items:
            - {label: "Home", route: home_route, icon: '<i class="fa fa-home"></i>' }
            - {label: "Item 1", route: item1_route, icon: '<i class="fa fa-gear"></i>' }
            ...
```

Then, to add the menu in the template :

``` twig
<div class="my_menu">
    {{knp_menu_render('menu_name', {'allow_safe_labels': true})}}
</div>
```

## Security

### Restrict access by roles

The `roles` option allow to control if an item is available to an user depending
on his authorizations. This option take an array of roles. The user must be
authorized for each of theses roles to see the item.

Example:

``` yaml
kalamu_menu_service:
    menu_name:
        items:
            - {label: "Home", route: home_route, icon: '<i class="fa fa-home"></i>', roles: ['ROLE_USER', 'ROLE_OPEN_DOOR'] }
```

To access the `home` item, the user must have both roles `ROLE_USER` and
`ROLE_OPEN_DOOR`.

### Restrict access by expression

The 'allow_if' option provide a more customizable way to handle the restrictions.
The option take an expression as argument. For more information about the syntax,
see the documentation about [How to use Expressions in Security](http://symfony.com/doc/current/expressions.html#security-complex-access-controls-with-expressions).

Example:

``` yaml
kalamu_menu_service:
    menu_name:
        items:
            - {label: "Home", route: home_route, icon: '<i class="fa fa-home"></i>', allow_if: "has_role('ROLE_USER') or has_role('ROLE_OPEN_DOOR')" }
```

To access the *Home* item, the user must have either the role `ROLE_USER` or
the role `ROLE_OPEN_DOOR`.

The option `roles` can be used in combination with the `allow_if` options.
In such case, both constraints must be satisfied  to grant access.

### Restrict access by hierarchy

On a menu with hierarchical items, most of the time the parent item has no sens
without his children. So it must be removed if all children are not accessible
to the current user.

If there is only a few underlying roles, this could be handled with the `allow_if`
option. But if the number of constraints grow, the task become uselessly
cumbersome.

The `hide_if_no_child` option solve this problem. If set to true, the item is
available only if the user has access to at least one child.

Example:

``` yaml
kalamu_menu_service:
    menu_name:
        items:
            -
                label: "User management"
                route: ''
                hide_if_no_child: true
                items:
                    - {label: "Users", route: user_list, roles: ['ROLE_MANAGE_USER'] }
                    - {label: "Groups", route: group_list, allow_if: '(user and user.isSuperAdmin())) or user.isGoodManager()' }
```


## Customisation

If you want to change interactivly you menu, you can use the events `kalamu.menu_service.configure.{menu_name}`.
For more informations on knp/menu-bundle : https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/events.md#create-a-listener
