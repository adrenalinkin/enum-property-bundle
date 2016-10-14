<?php

namespace Linkin\Bundle\EnumPropertyBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Choice;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class Enum extends Choice
{
    /**
     * @var string
     */
    public $domain;

    /**
     * @var array
     */
    public $exclude = [];

    /**
     * @var string
     */
    public $mapperName;

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return ['mapperName'];
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'enum_validator';
    }
}
