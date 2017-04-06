# MenuServiceBundle

This bundle provide a menu service for knp/menu-bundle that is configurable by config file.

## Installation

```
composer require kalamu/menu-service-bundle:^1.0
```

Puis ajouter dans `AppKernel.php`

```
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

```
kalamu_menu_service:
    menu_name:
        items:
            - {label: "Home", route: home_route, icon: '<i class="fa fa-home"></i>' }
            - {label: "Item 1", route: item1_route, icon: '<i class="fa fa-gear"></i>' }
            ...
```

Then, to add the menu in the template :

```
<div class="my_menu">
    {{knp_menu_render('menu_name', {'allow_safe_labels': true})}}
</div>
```

## Customisation

If you want to change interactivly you menu, you can use the events `kalamu.menu_service.configure.{menu_name}`.
For more informations on knp/menu-bundle : https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/events.md#create-a-listener
