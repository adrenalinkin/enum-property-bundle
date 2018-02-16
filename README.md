Enum Property Bundle [![На Русском](https://img.shields.io/badge/Перейти_на-Русский-green.svg?style=flat-square)](./README.RU.md)
====================

Introduction
------------

Bundle integrate [EnumMapper](https://github.com/adrenalinkin/enum-mapper) component with `Symfony` ecosystem.
Provides filters and functions for `Twig` and validator.

Installation
------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable
version of this bundle:
```text
    composer require adrenalinkin/enum-property-bundle
```
*This command requires you to have [Composer](https://getcomposer.org) install globally.*

### Step 2: Enable the Bundle

After enable the bundle by adding into list of the registered bundles into `app/AppKernel.php` of your project:

```php
<?php
// app/AppKernel.php

class AppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = [
            // ...

            new Linkin\Bundle\EnumPropertyBundle\LinkinEnumPropertyBundle(),
        ];

        return $bundles;
    }

    // ...
}
```

Usage
-----

Documentation for the [EnumMapper](https://github.com/adrenalinkin/enum-mapper/blob/master/README.RU.md) component.
As example we will use this class:

```php
<?php

use Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper;

class GenderMapper extends AbstractEnumMapper
{
    const DB_UNDEFINED = 0;
    const DB_MALE      = 10;
    const DB_FEMALE    = 20;

    const HUMAN_UNDEFINED = 'Undefined';
    const HUMAN_MALE      = 'Male';
    const HUMAN_FEMALE    = 'Female';
}
```

### Twig Extension

`Twig` extension allow to use `EnumMapper` component functionality from the templates.

#### enum_to_human

Get humanized value by received database value:

```twig
    {% set status = 20 %}
    {% set class = '\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper' %}
    {{ status|enum_to_human(class) }} {# Female #}
```

#### enum_to_db

Get database value by received humanized value:

```twig
    {% set status = 'Male' %}
    {% set class = '\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper' %}
    {{ status|enum_to_db(class) }} {# 10 #}
```

#### enum_map

Get full list of the available pairs of the database and humanized values:

```twig
    {% for key, value in enum_map('\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper') %}
        {{ key }}: {{ value|trans }} <br>
    {% endfor %}
    {# 
        0: Undefined
        10: Male
        20: Female 
    #}
```

#### enum_allowed_db and enum_allowed_human

Get list of the all available value for the database values or for the humanized values:

```twig
    {% set class = '\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper' %}

    {{ enum_allowed_db(class)|join(', ') }} {# 0, 10, 20 #}
    {{ enum_allowed_human(class)|join(', ') }} {# Undefined, Male, Female #}

    {# Exclude values from result #}
    {{ enum_allowed_db(class, [0])|join(', ') }} {# 10, 20 #}
    {{ enum_allowed_human(class, ['Undefined'])|join(', ') }} {# Male, Female #}
```

#### enum_random_db and enum_random_human

Get random database or humanized value:

```twig
    {% set class = '\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper' %}

    {{ enum_random_db(class) }} {# 0 || 10 || 20 #}
    {{ enum_random_human(class) }} {# Undefined || Male || Female #}

    {# Exclude values from result #}
    {{ enum_random_db(class, [0]) }} {# 10 || 20 #}
    {{ enum_random_human(class, ['Undefined']) }} {# Male || Female #}
```

### Enum Validator

For validate entity fields, which use `EnumMapper` component, bundle contain `EnumValidator`. Validator extends
`ChoiceValidator` from the standard `Symfony` package. Changed standard error message and `choice` array. Array `choice`
can not be changed and will be contain allowed database values of the received class-mapper. Also has been added several
additional options. Required option:

* `mapperName` - contains full name of the class-mapper.

Optional variable:

* `exclude` - List of the database values which should be excluded from the allowed values.

#### Validator usage example

```yaml
Acme\Bundle\AcmeBundle\Entity\User:
    properties:
        gender:
            - Linkin\Bundle\EnumPropertyBundle\Validator\Constraints\Enum:
                mapperName: 'Acme\Bundle\AcmeBundle\Entity\Mapper\GenderMapper'
                exclude:    [0]
```

License
-------

[![license](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](./LICENSE)
