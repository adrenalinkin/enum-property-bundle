EnumProperty Bundle
===================

Бандл базируется на компоненте `EnumMapper` (*будет ссылка на компонент*), интегрируя функционал
последнего с экосистемой `Symfony`. Поддерживает систему переводов, использование в Twig, DI и валидацию.

Расширение Twig
---------------

Предоставляется `Twig` расширение, позволяющее пользоваться функциональностью компонента
`EnumMapper` (*будет ссылка на компонент*) непосредственно из шаблонов. Имеется два метода:

* `fromDbToHuman` - для получения чловеко понятного значения на основании значения из базы данных.
* `fromHumanToDb` - для получения значения для базы данных на основании человеко-понятного представления.

Оба метода имеют схожие сигнатуры. Необходимо обязательно передать первым аргументом полное имя класса-карты,
а вторым параметром можно уточнить домен перевода, если вызывается `fromDbToHuman`. Если переданное первым параметром
имя класса не является наследником `AbstractEnumPropertyMapper`, то будет брошено исключение
`UnsupportedMapperException`.

### Примеры использования в Twig

1. Получение человеко-понятного значения:
``` twig
    {{ status|fromDbToHuman('\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper') }}
```
2. Получение человеко-понятного значения с уточнением домена переводов:
``` twig
    {{ status|fromDbToHuman('\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper', 'customDomain') }}
```
3. Получение значение в формате базы данных на основании человеко-понятного значения:
``` twig
    {{ status|fromHumanToDb('\\Acme\\Bundle\\AcmeBundle\\Entity\\Mapper\\GenderMapper') }}
```

Enum валидатор
--------------

Для быстрой и удобной проверки полей, использующих `Enum` в бандле создан валидатор `EnumValidator`. Данный валидатор
является расширением `ChoiceValidator`, входящего в поставку `Symfony`. Происходит переопределение стандартного
сообщения об ошибке и массива `choice`. Массив `choice` вы не сможете определить из вне, он всегда будет принимать
допустимые значения выбранного класса-карты, а также появляется несколько дополнительных опций. Одна обязательная:

* `mapperName` - содержит полное имя класса-карты. Если переданное имя класса не является наследником
    `AbstractEnumPropertyMapper`, то будет брошено исключение `UnsupportedMapperException`.

И две опциональных настройки:

* `domain` - домен перевода, который нужно использовать для получения человеко-понятных значений.
* `exclude` - массив исключений из списка допустимых значений класса-карты в формате базы данных.

### Пример использования валидатора

``` yml
Acme\Bundle\AcmeBundle\Entity\User:
    properties:
        gender:
            - Linkin\Bundle\EnumPropertyBundle\Validator\Constraints\Enum:
                mapperName: 'Acme\Bundle\AcmeBundle\Entity\Mapper\GenderMapper'
                domain:     'entities'
                exclude:    [0]
```

DI Сервис
---------

Абстракный класс `AbstractEnumPropertyMapper` позволет использовать систему переводов и зарегистрирован как сервис
`Symfony`. Найти сервис можно под идентификатором `linkin_enum_property.mapper.abstract`.

### Пример использования DI сервиса

``` yml
    parameters:
        acme_bundle.mapper.entity_gender.class:   Acme\Bundle\AcmeBundle\Mapper\EntityGenderMapper
    
    services:
        acme_bundle.mapper.entity_gender:
            class:      '%acme_bundle.mapper.entity_gender.class%'
            parent:     linkin_enum_property.mapper.abstract
```
