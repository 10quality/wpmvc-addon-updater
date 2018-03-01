# Wordpress MVC Addon Template
--------------------------------

Custom [Add-on](http://www.wordpress-mvc.com/v1/add-ons/) development template for [Wordpress MVC](http://www.wordpress-mvc.com/) addon development.

## Start up

Pick a unique namespace for your addon and change it in the following template files.

For the examples bellow, the picked namespace will be `WPMVC\AddonSamples`;

In **composer.json** file, from:
```
    "autoload": {
        "psr-4": {
            "AddonNamespace\\": "addon/"
        }
    },
```

To:
```
    "autoload": {
        "psr-4": {
            "WPMVC\\AddonSamples\\": "addon/"
        }
    },
```

And in **addon/Addon.php** file, from:
```php
namespace AddonNamespace;
```

To:
```php
namespace WPMVC\AddonSamples;
```

Optionally, change the name of the Addon class (`addon\Addon.php`) to one that suits more the functionality of the add-on.

For example, our addon will enable Facebook logins, so the addon class will be renamed to `FacebookLoginAddon`.