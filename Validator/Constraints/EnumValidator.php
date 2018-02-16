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

use Linkin\Bundle\EnumPropertyBundle\Exception\UnsupportedMapperException;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\ChoiceValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class EnumValidator extends ChoiceValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Enum) {
            throw new UnexpectedTypeException($constraint, sprintf('%s\Enum', __NAMESPACE__));
        }

        $mapperClass = $constraint->mapperName;

        if (!is_subclass_of($mapperClass, '\Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper')) {
            throw new UnsupportedMapperException();
        }

        /** @var \Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper $mapper */
        $mapper              = new $mapperClass();
        $constraint->choices = $mapper->getAllowedDbValues($constraint->exclude);

        parent::validate($value, $constraint);
    }
}
