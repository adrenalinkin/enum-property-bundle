Enum Property Bundle [![In English](https://img.shields.io/badge/Switch_To-English-green.svg?style=flat-square)](./README.md)
====================

Введение
--------

Бандл интегрирует компонент [EnumMapper](https://github.com/adrenalinkin/enum-mapper) с экосистемой `Symfony`.
Предоставляет набор фильтров и функций для `Twig` и валидатор.

Установка
---------

### Шаг 1: Загрузка бандла

Откройте консоль и, перейдя в директорию проекта, выполните следующую команду для загрузки наиболее подходящей
стабильной версии этого бандла:
```text
    composer require adrenalinkin/enum-property-bundle
```
*Эта команда подразумевает что [Composer](https://getcomposer.org) установлен и доступен глобально.*

### Шаг 2: Подключение бандла

После включите бандл добавив его в список зарегистрированных бандлов в `app/AppKernel.php` файл вашего проекта:

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

Использование
-------------

Документация к компоненту [EnumMapper](https://github.com/adrenalinkin/enum-mapper/blob/master/README.RU.md).
В качестве примера будем рассматривать следующий класс:

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

### Расширение Twig

Расширение `Twig` позволяет использовать компонент `EnumMapper` непосредственно из шаблонов.

#### enum_to_human

Получение человеко-понятного представление на основе данных из базы данных:

```twig
    {% set status = 20 %}
    {% set class = '\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper' %}
    {{ status|enum_to_human(class) }} {# Female #}
```

#### enum_to_db

Получение значения для хранения в базе данных на основе человеко-понятного представления:

```twig
    {% set status = 'Male' %}
    {% set class = '\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper' %}
    {{ status|enum_to_db(class) }} {# 10 #}
```

#### enum_map

Получение полного списка соответствий значений из базы данных и их человеко-понятных значений:

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

#### enum_allowed_db и enum_allowed_human

Получение списка всех доступных значений для базы данных и аналогичный метод для получения доступных человеко-понятных
значений:

```twig
    {% set class = '\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper' %}

    {{ enum_allowed_db(class)|join(', ') }} {# 0, 10, 20 #}
    {{ enum_allowed_human(class)|join(', ') }} {# Undefined, Male, Female #}

    {# Исключение значений из возвращаемого результата #}
    {{ enum_allowed_db(class, [0])|join(', ') }} {# 10, 20 #}
    {{ enum_allowed_human(class, ['Undefined'])|join(', ') }} {# Male, Female #}
```

#### enum_random_db и enum_random_human

Получение случайного значения одного из доступных значений базы данных или человеко-понятного представления:

```twig
    {% set class = '\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper' %}

    {{ enum_random_db(class) }} {# 0 || 10 || 20 #}
    {{ enum_random_human(class) }} {# Undefined || Male || Female #}

    {# Исключение значений из возвращаемого результата #}
    {{ enum_random_db(class, [0]) }} {# 10 || 20 #}
    {{ enum_random_human(class, ['Undefined']) }} {# Male || Female #}
```

### Enum валидатор

Для проверки использующих `EnumMapper` полей в бандле создан валидатор `EnumValidator`. Валидатор является расширением
`ChoiceValidator`, входящего в поставку `Symfony`. Происходит переопределение стандартного сообщения об ошибке и массива
`choice`. Массив `choice` вы не сможете определить из вне, он всегда будет принимать допустимые значения
выбранного класса. Также добавлены несколько дополнительных опций. Одна обязательная:

* `mapperName` - содержит полное имя класса.

Вторая опциональная переменная:

* `exclude` - массив исключений из списка допустимых значений класса-карты (значения в формате базы данных).

#### Пример использования валидатора

```yaml
Acme\Bundle\AcmeBundle\Entity\User:
    properties:
        gender:
            - Linkin\Bundle\EnumPropertyBundle\Validator\Constraints\Enum:
                mapperName: 'Acme\Bundle\AcmeBundle\Entity\Mapper\GenderMapper'
                exclude:    [0]
```

Лицензия
--------

[![license](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](./LICENSE)
