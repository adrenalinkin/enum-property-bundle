<?php

/*
 * This file is part of the LinkinEnumPropertyBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\EnumPropertyBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Choice;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class Enum extends Choice
{
    /**
     * List of the database values which should be excluded from the allowed values
     *
     * @var array
     */
    public $exclude = [];

    /**
     * Full name of the mapper class
     *
     * @var string
     */
    public $mapperName;

    /**
     * Override default choice error message
     *
     * @var string
     */
    public $message = 'linkin_enum_property.messages.unexpected_value';

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return ['mapperName'];
    }
}
